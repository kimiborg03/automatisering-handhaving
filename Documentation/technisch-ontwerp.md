# Technisch Ontwerp

[naam applicatie]

| Versie        |   |
|---------------|---|
| Datum         |   |
| Naam          |Kimi Borg   |Appie Khalid
| Studentnummer |158205      |174663

# Inhoud
1. [Inleiding](#inleiding)
2. [Functionaliteiten](#functionaliteiten)
3. [ERD](#entiteit-relationship-diagram-erd)
4. [Data-Dictionary](#datadictionary)
5. [Oplevering](#oplevering)


## Inleiding

Deze applicatie is gemaakt voor de opleiding Handhaving. Studenten van deze opleiding moeten stage lopen als beveiliger bij evenementen zoals sportwedstrijden. De applicatie is ontwikkeld om het aan- en afmelden voor deze wedstrijden te faciliteren, en om het beheer voor docenten (admins) eenvoudiger te maken.

- De applicatie is bedoeld voor onderwijsinstellingen met een handhavingsopleiding.
- Het programma wordt gemaakt om het proces van stagetoewijzing en aanwezigheidsregistratie te verbeteren.
- De doelgroep bestaat uit studenten en docenten van de opleiding.
- De applicatie is een webapplicatie die toegankelijk is via internet.

**Voorbeeld**

Dit is het technische ontwerp voor een webapplicatie die studenten van de opleiding Handhaving ondersteunt bij het aan- en afmelden voor evenementenstages, zoals sportwedstrijden.

De applicatie vervangt handmatige registratie en maakt het voor docenten eenvoudiger om deelname en aanwezigheid te beheren.

De doelgroep bestaat uit studenten en docenten van handhavingsopleidingen.

De applicatie is via internet toegankelijk en geschikt voor gebruik op zowel desktop als mobiel.


## Functionaliteiten

**Admins kunnen:**

- Wedstrijden toevoegen, bewerken en verwijderen
- Klassen (groepen) aanmaken, wijzigen en verwijderen
- Studenten toevoegen, bewerken en verwijderen

**Studenten kunnen:**

- Zich aanmelden voor wedstrijden
- Zich afmelden voor wedstrijden
- Wedstrijden ruilen met andere studenten


## Entiteit Relationship Diagram (ERD)

![](media/erd-automatiseringhandhaving.png)

## Datadictionary

*Voeg hier een lijst in met per tabel een opsomming van de gebruikte kolommen. Per kolom geeft je weer: de naam, het datatype, de lengte, of het een sleutel is en zoja naar welke tabel.]*

| **users**     |              |            |         |                  |
|---------------|--------------|------------|---------|------------------|
| **Kolom**     | **Datatype** | **Lengte** | **Key** | **Relatie naar** |
| id            | integer      |            | Primary |                  |
| name          | string       | 255*       |         |                  |
| username      | string       | 255*       |         |                  |
| password      | string       | 255*       |         |                  |
| email         | string       | 255*       |         |                  |
| group_id      | unsignedBigInteger |      | Foreign | groups           |
| role          | string       | 255*       |         |                  |
| access        | boolean      |            |         |                  |
| password_setup_token | string | 255*      |         |                  |
| email_verified_at | timestamp |           |         |                  |
| created_at    | timestamp    |            |         |                  |
| updated_at    | timestamp    |            |         |                  |


| **matches**   |              |            |         |                  |
|---------------|--------------|------------|---------|------------------|
| **Kolom**     | **Datatype** | **Lengte** | **Key** | **Relatie naar** |
| id            | integer      |            | Primary |                  |
| name          | string       | 255*       |         |                  |
| location      | string       | 255*       |         |                  |
| checkin_time  | datetime     |            |         |                  |
| kickoff_time  | datetime     |            |         |                  |
| category      | string       | 255*       |         |                  |
| comment       | text         |            |         |                  |
| users         | json         |            |         |                  |
| limit         | integer      |            |         |                  |
| deadline      | string       | 255*       |         |                  |
| groups        | json         |            |         |                  |
| created_at    | timestamp    |            |         |                  |
| updated_at    | timestamp    |            |         |                  |


| **groups**    |              |            |         |                  |
|---------------|--------------|------------|---------|------------------|
| **Kolom**     | **Datatype** | **Lengte** | **Key** | **Relatie naar** |
| id            | integer      |            | Primary |                  |
| name          | string       | 255*       |         |                  |
| created_at    | timestamp    |            |         |                  |
| updated_at    | timestamp    |            |         |                  |


| **password_reset_tokens**|              |            |         |                  |
|--------------------------|--------------|------------|---------|------------------|
| **Kolom**                | **Datatype** | **Lengte** | **Key** | **Relatie naar** |
| email                    | string       | 255*       | Index   |                  |
| token                    | string       | 255*       |         |                  |
| created_at               | timestamp    |            |         |                  |

## Oplevering


# ✅ Serverconfiguratie

- **Webserver**: Apache
- **PHP**: Versie 8.2 of hoger
- **Node.js**: 18+
- **Composer**: Versie 2.5+
- **MySQL**: Versie 8.0+

# ✅ Ontwikkelomgeving

- **Frameworks**: Laravel, Blade, TailwindCSS
- **Database**: MySQL
- **Mail**: SMTP-instellingen configureerbaar via adminpaneel


# Installatie-instructies

 Om MySQL te installeren kun je het officiële stappenplan volgen via deze link:  
[MySQL installatiehandleiding](https://dev.mysql.com/doc/mysql-installation-excerpt/8.0/en/)

Volg de instructies op de pagina voor jouw besturingssysteem om MySQL correct te installeren.

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