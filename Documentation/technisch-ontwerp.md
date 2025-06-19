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

*Beschrijf hier wat er gedaan moet worden om de applicatie op te leveren in een productieomgeving. De onderwerpen zijn per applicatie verschillend. Denk onder andere aan de volgende onderwerpen:*

-   Welke server wordt gebruikt, naam, versie, installatiestappen
-   Welke databaseserver wordt gebruikt, naam, versie, installatiestappen
-   Welke webserver wordt gebruikt
-   Welke ontwikkelplatform is gebruikt (PHP, .NET, JavaScript, enz.)
-   Welke framework is gebruikt (Laravel, ASP.NET, React, Angular, enz.)
-   Welke firewall is geïnstalleerd
-   Welke poorten zijn beschikbaar
