const fs = require("fs");
const clear = require("clear");
const figlet = require("figlet");
const inquirer = require("inquirer");
const chalk = require("chalk");

const questions = [
  {
    name: "server",
    type: "input",
    message: "Serveur",
    default: "localhost"
  },
  {
    name: "user",
    type: "input",
    message: "Utilisateur",
    default: "root"
  },
  {
    name: "password",
    type: "password",
    message: "Mot de passe"
  },
  {
    name: "database",
    type: "input",
    message: "Base de données",
    default: "oophp"
  },
  {
    name: "restart",
    type: "confirm",
    message: "Configuration terminée ?"
  }
];

const shuffle = a => {
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
};

const template = answers => `<?php

// Nom d'hôte
define('SQL_HOST', '${answers.server}');

// Nom de la base de données
define('SQL_NAME', '${answers.database}');

// Login d'identification
define('SQL_USER', '${answers.user}');

// Mot de passe
define('SQL_PWD', '${answers.password}');
`;

const generateFile = answers => {
  fs.writeFile(
    "./public/config/sql.php",
    template(answers),
    "utf-8",
    err =>
      err
        ? console.log(chalk.red("Une erreur est survenue."))
        : console.log(
            chalk.green("\nConfiguration terminée.\nLancez simplement : ") +
              chalk.inverse.green("npm start\n")
          )
  );
};

(init = i => {
  const suffix = i === 0 ? "" : " N°" + (i + 1);

  let colors = ["blue", "cyan", "magenta", "red", "yellow", "green"];
  colors = shuffle(colors);

  clear();

  if (i > 2) {
    return console.log(
      chalk.red(
        `Vous avez déjà essayé ${i} fois sans être convaincu. Retentez plus tard avec la commande : `
      ) + chalk.red("npm run postinstall")
    );
  }

  console.log(chalk[colors[i]](figlet.textSync("OOPHP config." + suffix)));

  inquirer
    .prompt(questions)
    .then(answers => (!answers.restart ? init(i + 1) : generateFile(answers)));
})(0);
