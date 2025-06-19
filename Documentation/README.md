# Documentation

# Installatie-instructies

1. Open de terminal in de map `automatisering_handhaving`.
2. Voer uit:  
   ```
   composer install
   ```
3. Wacht tot dit klaar is en voer uit:  
   ```
   npm install
   ```
4. Wacht tot dit klaar is en voer uit:  
   ```
   cp .env.example .env
   ```
5. Voer uit:  
   ```
   php artisan key:generate
   ```
   - Als je de melding krijgt:  
     `The database 'automatisering_handhaving' does not exist on the 'mysql' connection. Would you like to create it? (yes/no) [yes]`  
     Typ: `yes`
6. Voer uit:  
   ```
   npm run build
   ```
7. Start de server:  
   ```
   php artisan serve
   ```
8. Maak een admin gebruiker aan:  
   ```
   php artisan db:seed
   ```

**Inloggegevens admin:**  
- E-mail: `jan@example.com`  
- Wachtwoord: `wachtwoord123`

---

**Optioneel:**  
- Ga naar `/admin/users` om gebruikersinformatie van dit account aan te passen.  
- Wachtwoord wijzigen kan op `/account`.

---

## 📧 Mailinstellingen

1. Ga naar `admin/mail-settings` in het menu.
2. Vul de mailgegevens in voor de server die de mails verstuurt (host, poort, gebruikersnaam, wachtwoord, afzender).
3. Klik op Opslaan.

Deze instellingen zijn nodig om e-mails te kunnen versturen (zoals registratie of wachtwoordherstel).