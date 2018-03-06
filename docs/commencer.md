# ![#03a9f4](https://placehold.it/15/03a9f4/000000?text=+) Lancer le projet

Sur ce projet est utilisé `npm` mais rien ne vous interdit d'utiliser `yarn`.

## ![#4caf50](https://placehold.it/15/4caf50/000000?text=+) Installation

``` bash
$ npm i # npm install

# ou

$ yarn # yarn install
```

À la fin de l'installation des dépendances des questions vous seront posées pour configurer votre connexion à la base de données.
Suivez les étapes, puis dans votre gestionnaire de base de données, [importez le fichier sql](../public/db).


## ![#4caf50](https://placehold.it/15/4caf50/000000?text=+) Scripts

Afin de générer des fichiers compréhensible par votre navigateur il faudra lancer un script npm.
```bash
$ npm start # npm run start

# ou

$ yarn start # yarn run start
```

Ce script lance webpack, vous n'avez ensuite plus rien à faire.
Si les différents linter détectent une erreur, elles seront listé dans votre terminal.
