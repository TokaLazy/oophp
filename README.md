# Site du Savoir MVC/OOP

> Refonte du [repository du Site du Savoir](https://github.com/malnuxstarck/Sitedusavoir) avec une archi MVC.

Le projet n'a pour l'instant été testé que sur Windows en utilisant WampServer pour gérer le PHP.
Si vous êtes sur Linux ou Mac. Il y a des chances pour que cela ne fonctionne pas. Si c'est le cas, merci d'ouvrir une Issue.


## Contribution
Si l'envie vous dit de participer sur ce projet, ne soyez pas timide. Suivez simplement les instructions [ci-joint](contribution.md).

## Les builds
Les scripts de build sont gérés avec Gulp. Il y en a deux différents.

### Build de développement
```shell
yarn start
# ou
npm start
```
Ce build de dev permet de compiler à la volé les fichiers scss et javascript. Un sourcemap y est incorporé pour faciliter le débogage.

### Build de production
```shell
yarn prod
# ou
npm prod
```
Avec ce build, un dossier `public` est créé et contient la totalité des fichiers nécessaire au bon fonctionnement du site en prod. Les assets sont minifier.


## Architecture
Afin de faciliter la maintenabilité du code plusieurs dossiers sont disponibles:
- [config](#config)
- [controllers](#controllers)
- [db](#db)
- [include](#include)
- [models](#models)
- [resources](#resources)
- [views](#views)

### Config
Contient les fichiers nécessaires et obligatoire pour le fonctionnement du projet. Il contient un fichier caché `sql.php` qui contient les identifiants pour la connexion à la base de données. Il suffit de se baser sur le fichier `sql.exemple.php`.

### Controllers
Contient les "controller" du MVC. On y traite les informations reçu du model, ainsi que la gestion des routes.

### Db
Contient au minimum la dernière version du la base de données à utiliser.

### Include
Assez difficile à expliquer... On dire pour l'instant que ce dossier contient les fichiers qui n'entrent pas dans les critères des autres dossiers.

### Models
Contient les "model" du MVC. Tout les requêtes à la base de donnée s'y trouve.

### Resources
Contient les assets. Font, feuille de style, javascript et images y sont rangés.

### Views
Contient les "vue" du MVC. A savoir le rendu visuel affiché sur le navigateur.
