# Dépôt du projet : [GitHub](https://github.com/qriosserra/stalker)
#### HTTPS : `https://github.com/qriosserra/stalker.git`
#### SSH : `git@github.com:qriosserra/stalker.git`

# Mettre en place le projet

- S'assurer que le `.env` est bien configuré
- Exécuter les commandes suivantes :

```shell
docker exec -it but3-web-container-server-1 bash
```

```shell
cd stalker/
```

```shell
composer install
```

```shell
php bin/console doctrine:database:create
```

```shell
php bin/console doctrine:schema:update --force
```

```shell
php bin/console doctrine:fixtures:load
```
- **Entrer :** yes

```shell
npm install
```

```shell
npm run build
```

- Le site sera disponible sur ce lien : http://localhost:80/stalker/public/

# Investissement de chacun

- #### Peter POIRRIER : 33%
  - Liste des utilisateurs back & front
  - Modification du code de profil utilisateur
  - Participation à la création du back & front du profil utilisateur
  - Mise en place du système de drapeau
  - Création des commandes
  - Système d'authentification pour les réseaux sociaux (ne marche que pour steam, je n'ai pas eu le temps de le rendre générique)
- #### Nikhil RAM : 32%
  - Formulaire + front de la page utilisateur
- #### Quentin RIOS-SERRA : 34%
  - Initialisation du projet
  - Mise en place du système de pop-up
  - Création du sign-up, sign-in et sign-out back & front
  - Création du UserListener
  - Redirection sur le code de profil custom (enlevé)
  - Ajout des fixtures


# Fonctionnement de l'application

## 1. Page d'accueil
À l'ouverture de l'application, vous êtes dirigé vers la page d'accueil, qui affiche une liste des utilisateurs.

## 2. Accès au profil utilisateur
En cliquant sur le nom d'un utilisateur dans la liste, vous êtes redirigé vers son profil. La page de profil présente diverses informations sur l'utilisateur ainsi que plusieurs boutons d'action.

## 3. Boutons d'action sur le profil
- **Connexion Steam :** Permet à l'utilisateur de lié son compte steam.
- **Modifier le profil :** Redirection vers un formulaire de modification du profil de l'utilisateur.
- **Supprimer le compte :** Permet de supprimer le compte de l'utilisateur.
- **Modifier le code :** Ouvre une pop-up pour modifier le code de l'utilisateur.

## 4. Navigation
Une barre de navigation (nav bar) est présente sur toutes les pages, offrant les fonctionnalités suivantes :
- **Sign In :** Accès au compte utilisateur.
- **Sign Up :** Permet de créer un utilisateur
- **Annuaire :** Redirection vers la page d'accueil.
- **Account :** L'utilisateur peut accéder directement à sa page de profil s'il est connecté.
- **Disconnect :** Permet à l'utilisateur de quitter sa session.




# Routes de l'application

| Méthode     | Route                                                                                                                        | Description                                     |
|-------------|------------------------------------------------------------------------------------------------------------------------------|-------------------------------------------------|
| GET         | [`/`](http://localhost:80/stalker/public/)                                                                                   | Page d'accueil                                  |
| GET         | [`/users`](http://localhost:80/stalker/public/sign-in)                                                                       | Page recensant les utilisateurs                 |
| GET         | [`/users/{profilCode}`](http://localhost:80/stalker/public/sign-in)                                                          | Page d'accès aux comptes                        |
| GET         | [`/users/{profileCode}/json`](http://localhost:80/stalker/public/sign-in)                                                    | Page d'affichage des données utilisateur        |
| GET, POST   | [`/account/update`](http://localhost:80/stalker/public/account/update)                                                       | Action de modification d'un utilisateur         |
| GET, DELETE | [`/account/{profileCode}/delete`](http://localhost:80/stalker/public/account/{profileCode}/delete)                           | Action de suppression d'un utilisateur          |
| POST        | [`/account/update-profile-code`](http://localhost:80/stalker/public/account/update-profile-code)                             | Action de modification d'un code utilisateur    |
| POST        | [`/api/users/check-profile-code-availability`](http://localhost:80/stalker/public/api/users/check-profile-code-availability) | Vérification de la disponibilité d'un code      |
| POST        | [`/users/{profileCode}/reset-profile-code`](http://localhost:80/stalker/public/users/{profileCode}/reset-profile-code)       | Régénération d'un code par défaut               |
| GET         | [`/steam/connect`](http://localhost:80/stalker/public/steam/connect)                                                         | Action de connexion à Steam                     |
| GET         | [`/steam/check`](http://localhost:80/stalker/public/steam/check)                                                             | Action de liaison entre Steam et l'utilisateur  |
| POST        | [`/sign-up`](http://localhost:80/stalker/public/sign-up)                                                                     | Action d'inscription                            |
| POST        | [`/sign-in`](http://localhost:80/stalker/public/sign-in)                                                                     | Action de connexion                             |

# Commande

- **Promouvoir un utilisateur en tant qu'administrateur :**
```bash
  php bin/console promote:admin {userCode}
 ```
- **Démouvoir un utilisateur de son statut d'administrateur :**
```bash
  php bin/console promote:admin {userCode}
 ```