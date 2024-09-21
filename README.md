# Dépôt du projet : [GitHub](https://github.com/qriosserra/stalker)
#### HTTPS : `https://github.com/qriosserra/stalker.git`
#### SSH : `git@github.com:qriosserra/stalker.git`

# Mettre en place le projet

```shell
docker exec -it but3-web-container-server-1 bash
```

```shell
php bin/console doctrine:database:create
```

```shell
php bin/console doctrine:schema:update --force
```

```shell
npm run watch
```

http://localhost:80/stalker/public/

# Investissement de chacun

- #### Peter POIRRIER : ##%
  - M'a invité dans sa famille Steam et aime Thibaut (et c'est réciproque)
- #### Nikhil RAM : ##%
  - N'a toujours pas fini Outer Wilds (vivement qu'il retourne en Inde)
- #### Quentin RIOS-SERRA : ##%
  - Tout.

# Routes de l'application
- [`GET` `/`](http://localhost:80/stalker/public/) : Page d'accueil
- [`GET` `/user`](http://localhost:80/stalker/public/sign-in) : Page recensant les utilisateurs
- [`GET` `/user/{id}`](http://localhost:80/stalker/public/sign-in) : Page d'acces aux comptes
- [`GET` `/user/{id}/delete`](http://localhost:80/stalker/public/sign-in) : Action de suppression d'un utilisateur
- [`POST` `/sign-up`](http://localhost:80/stalker/public/sign-up) : Action d'inscription
- [`POST` `/sign-in`](http://localhost:80/stalker/public/sign-in) : Action de connexion