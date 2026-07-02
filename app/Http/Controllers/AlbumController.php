<?php
namespace App\Http\Controllers;
use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    //Helper pour vérifier la validité de l'abonnement
    private function isSubscriptionActive($user)
    {
        if (!$user) return false;

        return $user->subscriptions()
            ->where('stripe_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
            })
            ->exists();
    }
    // Afficher le formulaire avec la liste des artistes
    public function create()
    {
        // On récupère les artistes pour les envoyer au formulaire Vue
        // $artists = Artist::orderBy('surname', 'asc')->get();

        // return Inertia::render('Albums/Create', [
        //     'artists' => $artists
        // ]);

        $user = Auth::user();
        $artist = $user?->artist;

        // 1. Déterminer le statut de l'abonnement
        $isSubscriptionActive = $this->isSubscriptionActive($user);

        // 2. Compter le nombre d'albums actuels de l'artiste
        $albumCount = $artist ? Album::where('artist_id', $artist->id)->count() : 0;

        // 3. Définir la limite selon le plan (à adapter selon tes colonnes Stripe/Plans)
        // Exemple : si l'abonnement est actif, limite à 10, sinon plan gratuit limité à 2 (ou 5)
        $albumLimit = $isSubscriptionActive ? 10 : 5;

        //$artists = Artist::select('id', 'surname')->get();
        return Inertia::render('music/album/Create', [
            'isArtist'             => !is_null($artist) && $artist->status === 'approved',
            'isSubscriptionActive' => $isSubscriptionActive,
            'albumCount'           => $albumCount,
            'albumLimit'           => $albumLimit,
            'artists'              => Artist::select('id', 'surname')->get()
        ]);
    }

    // Enregistrer l'album
    public function store(Request $request)
    {
        $user = Auth::user();
        $artist = Artist::where('user_id', $user->id)->first();

        // Sécurité 1 : Est-ce un artiste approuvé ?
        if (!$artist || $artist->status !== 'approved') {
            return redirect()->back()->withErrors(['error' => 'Vous devez avoir un profil artiste approuvé pour créer un album.']);
        }

        // Sécurité 2 : L'abonnement est-il actif ?
        if (!$this->isSubscriptionActive($user)) {
            return redirect()->back()->withErrors(['error' => 'Votre abonnement a expiré. Veuillez le renouveler.']);
        }

        // Sécurité 3 : Vérification stricte de la limite du plan
        $albumCount = Album::where('artist_id', $artist->id)->count();
        $albumLimit = 2; // Doit correspondre à la même logique que le create()

        if ($albumCount >= $albumLimit) {
            return redirect()->back()->withErrors(['error' => "Vous avez atteint la limite maximale de {$albumLimit} albums pour votre plan actuel."]);
        }

        // Validation des données reçues du formulaire
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'release_year' => 'required|date',
            'image'        => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_free'      => 'required|boolean',
            'price'        => 'nullable|required_if:is_free,false|numeric|min:0',
        ]);

        // Génération automatique du slug unique à partir du titre
        $validated['slug'] = Str::slug($request->title) . '-' . uniqid();

        // Gestion du téléversement de l'image
        if ($request->hasFile('image')) {
            // Sauvegarde dans storage/app/public/albums
            $path = $request->file('image')->store('albums', 'public');
            $validated['image'] = $path;
        }

        // Conversion de la valeur du checkbox en booléen
        $validated['is_free'] = $request->boolean('is_free');
        $artist = Artist::where('user_id', Auth::id())->first();

        if (!$artist) {
            return redirect()->back()->withError(["error" => "Vous devez avoir un profil artiste pour créer un album."]);
        }
        $validated['artist_id'] = $artist->id;

        // Si l'album est gratuit, le prix est mis à null
        if ($validated['is_free']) {
            $validated['price'] = null;
        }

        // Création de l'album dans la base de données
        Album::create($validated);

        // Redirection vers la liste des albums avec un message de succès

        return redirect("/albums")->with('success', 'Album créé !');
    }
}
