# MINI APPLICATION DE GESTION DES PROJECTS POUR TRAVAUX PUBLICS

cette mini application est basé sur le language PHP et utilise le framework Symfony. elle open source et est destiné à assister les petites et moyennes entreprises du secteurs des travaux publiques dans le pilotage des projects qui leurs sont confiés.

la gestion dynamique côté client est gérée par la library UX LiveComponent de Symfony.

## FONCTIONNALITES

- **Projets**
    - Ajout nouveau projet
    - Edition et suppression des projets
    - Filtrage simple des projets
    - Filtrage combiné des projets

- **Utilisateurs**
    - Ajout nouveau utilisateur
    - Assignation et édition du rôle des utilisateurs
    - Blocage et déblocage des utilisateurs
    - Edition des information d'un utilisateur
    - Edition des identifiants de connexion

- **Types de projet**
    - Ajout nouveau type
    - Edition et suppression des types de projets

- **Secteurs**
    - Ajout nouveau secteurs
    - Edition et suppression des types de secteurs\
    
- **Prestataires**
    - Ajout nouveau prestataire
    - Edition et suppression des prestataires


## INSTALLATION

dépôt est destiné aux dévéloppeurs qui peut télécharger le code du projet sur sa machine en local et le modifié à sa guise pour obtenir l'application désirée, ou s'en inspirer pour créer une autre application similaire.

    Si vous rechercher une application de pilotage des projets directement utilisable, la version complète installable de ce code se trouve sur ce dépôt. Rendez-vous à la page du projet, suivez les instructions d'installation et utilisez l'application sans aucune restriction.
[cliquez ici pour aller à la page de téléchargement de cette version installable](https://github.com/Codinards/application-pilotage-des-projets-btp).

Pour télécharger le code de ce projet sur votre machine, suivez les étapes ci-dessous:

-   **git clone https://github.com/Codinards/pilotage-projets-btp.git nom_du_projet**. remplacez **nom_du_projet** par le nom du dossier dans lequel vous voulez cloner le code du projet.
-   **cd nom_du_projet**. Pour vous situer sur le dossier du projet.
-   **composer install**.
-   **yarn ou npm install**.
-   **php -S localhost:8000 -t public/**  pour lancer le serveur interne de php.
-   Ouvrez votre navigateur favori et entrez **localhost:8000** dans la bare de navigation.

    **INFORMATIONS D'AUTHENTIFICATION**

    - Identifiant: administrateur;
    - Mot de passe: administrateur.