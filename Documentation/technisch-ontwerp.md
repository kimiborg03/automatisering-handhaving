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

*Schrijf hier een korte inleiding over het programma. Denk bijvoorbeeld aan de volgende punten:*

-   Voor welk bedrijf wordt het programma gemaakt
-   Waarom wordt het programma gemaakt
-   Welke doelgroep gaat het programma gebruiken (klanten, medewerkers, Internetgebruikers, enzovoort)
-   Wat voor soort programma maak je (webapplicatie, desktopapplicatie, mobile app, enzovoort)

**Voorbeeld**

Dit is het technische ontwerp voor het nieuwe administratieprogramma van Bakker Bartels. Deze applicatie gaat het bestaande desktop programma vervangen.

De applicatie gaat gebruikt worden door de administratieafdeling van het bedrijf.

Er wordt een webapplicatie gemaakt die ook op een mobiel goed te gebruiken moet zijn.


## Functionaliteiten



*Maak een lijst met de functionaliteiten uit het functioneel ontwerp of je opdracht.*

*Als er bijzonderheden zijn dan beschrijf je die ook.*

**Voorbeeld**

-   Invoeren van nieuwe medewerkers
-   Wijzigen medewerkers
-   Verwijderen medewerkers
    -   Bij het verwijderen van de medewerker wordt ook de pas geblokkeerd


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
