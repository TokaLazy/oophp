# ![#03a9f4](https://placehold.it/15/03a9f4/000000?text=+) Architecture


## ![#4caf50](https://placehold.it/15/4caf50/000000?text=+) Organisation des fichiers

Afin de faciliter la maintenabilité du code plusieurs dossiers sont disponibles:
- [config](#config)
- [controllers](#controllers)
- [db](#db)
- [include](#include)
- [models](#models)
- [resources](#resources)
- [views](#views)

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Config
Contient les fichiers nécessaires et obligatoire pour le fonctionnement du projet. Il contient un fichier caché `sql.php` qui contient les identifiants pour la connexion à la base de données. Il suffit de se baser sur le fichier `sql.exemple.php`.

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Controllers
Contient les "controller" du MVC. On y traite les informations reçu du model, ainsi que la gestion des routes.

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Db
Contient au minimum la dernière version du la base de données à utiliser.

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Include
Assez difficile à expliquer... On dire pour l'instant que ce dossier contient les fichiers qui n'entrent pas dans les critères des autres dossiers.

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Models
Contient les "model" du MVC. Tout les requêtes à la base de donnée s'y trouve.

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Resources
Contient les assets. Font, feuille de style, javascript et images y sont rangés.

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Views
Contient les "vue" du MVC. A savoir le rendu visuel affiché sur le navigateur.


## ![#4caf50](https://placehold.it/15/4caf50/000000?text=+) Outils pratiques

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) EditorConfig
Pour éviter les modifications trop importantes dues aux tabulations (entre autres), un fichier de configuration `.editorconfig` est mit à disposition à la racine du projet. [Installez le plugin](http://editorconfig.org/#download) sur votre IDE afin d'en profiter.

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Csscomb
*Prochainement arrivera un fichier de configuration pour [CSScomb](http://csscomb.com)...*

---

### ![#e91e63](https://placehold.it/15/e91e63/000000?text=+) Eslint
Eslint est incorporé au projet via un loader webpack. Le plugin prettier est en charge des règles à respecter.
