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

http://localgost:80/stalker/public/

# Investissement de chacun

- #### Peter POIRRIER : ##%
  - M'a invité dans sa famille Steam
- #### Nikhil RAM : ##%
  - N'a toujours pas fini Outer Wilds
- #### Quentin RIOS-SERRA : ##%
  - Tout.

# Routes de l'application
- [`GET` `/`](http://localhost:80/stalker/public/) : Page d'accueil
- [`POST` `/sign-up`](http://localhost:80/stalker/public/sign-up) : Action d'inscription
- [`POST` `/sign-in`](http://localhost:80/stalker/public/sign-in) : Action de connexion