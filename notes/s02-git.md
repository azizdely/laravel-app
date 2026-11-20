# Session 02 - Git et GitHub

Réponses rédigées avec mes propres mots, puis comparées au corrigé de la page.

## 1. Index et commit

L’index, aussi appelé zone de préparation, contient les changements que l’on a sélectionnés pour le prochain commit. La commande `git commit` enregistre ces changements dans l’historique local du dépôt, avec un message qui décrit le travail effectué.

## 2. Branche et tag

Une branche est un pointeur qui avance au fil des commits et permet de travailler sur une évolution séparément. Un tag est un repère posé sur un commit précis, souvent pour identifier une version publiée.

## 3. Le fichier .env

Le fichier `.env` contient les réglages propres à l’environnement local, par exemple les identifiants de connexion à la base de données. Il ne faut pas le publier dans Git, car il peut contenir des informations privées. Le fichier `.env.example` sert de modèle partageable.

## 4. Le dossier vendor/

Le dossier `vendor/` contient les bibliothèques PHP installées par Composer. Il est généralement ignoré par Git : les dépendances peuvent être réinstallées à partir de `composer.json` et `composer.lock` avec `composer install`.

## 5. Git Credential Manager

Git Credential Manager aide Git à s’authentifier auprès d’un service comme GitHub. Il peut conserver les identifiants de manière sécurisée afin d’éviter de saisir un jeton à chaque opération ou de l’écrire dans l’URL du dépôt.