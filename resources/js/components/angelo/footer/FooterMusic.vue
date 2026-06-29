<script setup>
import { Link } from '@inertiajs/vue3';
import logo from '@/assets/images/jbl.png'

// Optionnel : Récupérer l'année en cours dynamiquement
const currentYear = new Date().getFullYear();
</script>

<template>
  <footer class="backdrop-blur-md bg-gradient-to-r from-neutral to-neutral/10 text-neutral-content p-10 font-sans rounded-tr-[2rem]">

    <!-- Section principale avec Grille -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-8 mb-10">

      <!-- Colonne 1: Logo / Nom du projet -->
      <div class="md:col-span-1">
        <Link href="/" class="flex items-center gap-2 text-white font-bold text-xl tracking-tight hover:text-primary transition duration-200">
          <!-- Icône style Spotify (SVG personnalisé) -->
          <img :src="logo" alt="logo" class="w-10">
          <span>MyLife</span>
        </Link>
      </div>

      <!-- Colonne 2: Entreprise -->
      <div>
        <h6 class="footer-title text-gray-400 font-bold uppercase tracking-wider text-xs mb-4">Entreprise</h6>
        <div class="flex flex-col gap-3">
          <Link href="/about" class="link link-hover text-sm text-gray-300 hover:text-white transition">À propos</Link>
          <Link href="/jobs" class="link link-hover text-sm text-gray-300 hover:text-white transition">Emplois</Link>
          <Link href="/press" class="link link-hover text-sm text-gray-300 hover:text-white transition">For the Record</Link>
        </div>
      </div>

      <!-- Colonne 3: Communautés -->
      <div>
        <h6 class="footer-title text-gray-400 font-bold uppercase tracking-wider text-xs mb-4">Communautés</h6>
        <div class="flex flex-col gap-3">
          <Link href="/artists" class="link link-hover text-sm text-gray-300 hover:text-white transition">Espace artistes</Link>
          <Link href="/developers" class="link link-hover text-sm text-gray-300 hover:text-white transition">Développeurs</Link>
          <Link href="/advertising" class="link link-hover text-sm text-gray-300 hover:text-white transition">Publicité</Link>
          <Link href="/investors" class="link link-hover text-sm text-gray-300 hover:text-white transition">Investisseurs</Link>
        </div>
      </div>

      <!-- Colonne 4: Liens utiles -->
      <div>
        <h6 class="footer-title text-gray-400 font-bold uppercase tracking-wider text-xs mb-4">Liens utiles</h6>
        <div class="flex flex-col gap-3">
          <Link href="/support" class="link link-hover text-sm text-gray-300 hover:text-white transition">Assistance</Link>
          <Link href="/player" class="link link-hover text-sm text-gray-300 hover:text-white transition">Lecteur Web</Link>
          <Link href="/app" class="link link-hover text-sm text-gray-300 hover:text-white transition">Appli mobile gratuite</Link>
        </div>
      </div>

      <!-- Colonne 5: Réseaux Sociaux (Boutons circulaires daisyUI) -->
      <div class="flex md:justify-end gap-4 items-start">
        <a href="https://instagram.com" target="_blank" class="btn btn-circle bg-neutral bg-base-300 hover:bg-neutral border-none text-white">
          <svg xmlns="http://w3.org" class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </a>
        <a href="https://twitter.com" target="_blank" class="btn btn-circle bg-neutral bg-base-300 hover:bg-neutral border-none text-white">
          <svg xmlns="http://w3.org" class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
        </a>
      </div>

    </div>

    <!-- Séparateur Horizontal -->
    <div class="divider before:bg-base-300 after:bg-base-300 my-6"></div>

    <!-- Section Basse (Mentions légales et Copyright) -->
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">

      <!-- Liens légaux -->
      <div class="flex flex-wrap gap-x-6 gap-y-2 justify-center md:justify-start">
        <Link href="/legal" class="hover:text-white transition">Légal</Link>
        <Link href="/privacy" class="hover:text-white transition">Centre de confidentialité</Link>
        <Link href="/cookies" class="hover:text-white transition">Cookies</Link>
        <Link href="/ads-info" class="hover:text-white transition">À propos des publicités</Link>
        <Link href="/accessibility" class="hover:text-white transition">Accessibilité</Link>
      </div>

      <!-- Droits d'auteur -->
      <div class="text-center md:text-right">
        <p>&copy; {{ currentYear }} MyLife. Tous droits réservés.</p>
      </div>

    </div>
  </footer>
</template>
<style>
</style>
