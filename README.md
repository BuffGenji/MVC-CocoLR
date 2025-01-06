
# **Covoiturage La Rochelle**  

## **Description**  
Ce projet est une application web de covoiturage conçue spécifiquement pour la communauté de **La Rochelle**. L'objectif est de fournir une plateforme simple et efficace permettant aux utilisateurs de :  
- Créer un compte utilisateur.  
- Publier des trajets de covoiturage avec des informations supplémentaires pertinentes.  
- Rechercher et sélectionner des trajets proposés par d'autres utilisateurs (*fonctionnalité en cours de développement*).  

L'application utilise :  
- **Slim Framework** : pour le rendu des templates et la gestion du modèle MVC.  
- **Mapbox** : pour l'affichage et la gestion des cartes interactives.  
- **JavaScript** : pour les interactions dynamiques côté client.

PHP a été utilisé pour le backend et la gestion de la logique de l'application. Pour offrir une meilleure expérience utilisateur, **JavaScript** a été employé pour l'intégration fluide de l'API Mapbox. Bien que cela aurait pu être fait uniquement avec **PHP**, ce choix garantit une meilleure réactivité et fluidité de l'interface.

---

## **Fonctionnalités**  

- **Création de compte utilisateur** : permet aux membres de la communauté de s’inscrire.  
- **Calcul de trajet** : une fois un itinéraire défini, l’application peut calculer le trajet et l’afficher sur la carte.  
- **Ajout de trajets** : les utilisateurs peuvent poster un trajet avec des détails supplémentaires.  
- **Interface dynamique** : l’interface web est fluide et interactive grâce à l’utilisation conjointe de PHP et JS.  

*(Note : La sélection des trajets est encore en cours de développement.)*

---

## **Prérequis**  
Pour exécuter ce projet en local, vous aurez besoin de :  
- **PHP 7.4+**  
- **Composer** (pour gérer les dépendances PHP)  
- **Slim Framework** installé  
- Une clé API **Mapbox** (gratuite avec une limite d’utilisation)  
- Un serveur web local (par exemple : XAMPP, WAMP ou MAMP)  

---

## **Installation**  

1. Clonez le dépôt :  
   ```bash  
   git clone https://github.com/votre-utilisateur/covoiturage-la-rochelle.git  
   cd covoiturage-la-rochelle  
   ```  

2. Installez les dépendances via Composer :  
   ```bash  
   composer install  
   ```  

3. Configurez l'application :  
   Dans l'application il y a des constantes. Pour la cle de Mapbox, la constante se trouve 
    : "app/Templates/js/elements.js"

4. Lancez le serveur local :  
   ```bash  
   php -S localhost:8000/signup  
   ```  

5. Ouvrez l’application dans votre navigateur à l’adresse :  
   ```plaintext  
   http://localhost:8000/signup  
   ```  

---

## **Utilisation**  

### **Publication de trajet**  
Une fois connecté, vous pouvez :  

1. Calculer un trajet via l’interface de Mapbox en sélectionnant les points de départ et d’arrivée.  
2. Ajouter des informations comme la date, l'heure, points de départ et d’arrivée ...  
3. Publier le trajet.

---

## **Arborescence du projet**  
```plaintext  
app/  
├── src/                 # Contient le code source PHP (contrôleurs, modèles, vues)  
     ├── AbstractClasses/           
     ├── Config/         # fichiers de config + BDD/PDO
     ├── Controllers/
     ├── Dev/            # Gere la creation des routes via JSON
     ├── Entities/ 
     ├── Models/         
     ├── Services/       # Les different outils du project (faire du SQL, middleware, management des Sessions ...)
     └── Templates/      # Ici sont les Views du MVC et leurs css + js correspondant
            ├── css/
            ├── js/
            .
            .
            
├── vendor/              # Dépendances installées via Composer    
├── composer.json        # Définition des dépendances PHP  
└── README.md            # Documentation du projet  
```  

---

## **Technologies utilisées**  
- **PHP 7.4+**  
- **Slim Framework**  
- **JavaScript (ES6)**  
- **Mapbox API**  
- **HTML5 / CSS3**  

---

## **Licence**  
Ce projet est sous licence **MIT**. Vous pouvez consulter le fichier [LICENSE](LICENSE) pour plus d'informations.  

---

## **Remarques finales**  
Certaines fonctionnalités, comme la sélection des trajets, sont encore en développement. Si vous souhaitez contribuer à ce projet ou avez des suggestions, n’hésitez pas à me contacter.  

---

## **Avertissement**  
Cette application utilise des services tiers comme **Mapbox**, qui disposent d’une version gratuite avec certaines limitations. Assurez-vous de lire et de comprendre leurs conditions d'utilisation avant de déployer cette application publiquement.
