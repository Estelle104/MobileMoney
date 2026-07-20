# Initialisation du projet 
- Creation du depot github (etu004185)
- Mise en place du template codeigniter (etu004219) 
- Conception de la base de donnees (etu004185, etu004219)

# Routage
- Creer les routes et les proteger: (etu004185)
    - /operateur 
    - /client

# Operateur (etu004185)
## Login Operateur
- ROute 
    - /operateur/login
    - /operateur/checklogin
- Model
    - creation de operateur
- Controller
    - fonction checkLogin (email, mdp)
    - redirection /operateur/login
- Vue
    - /operateur/login

## Configuration des prefixes
- Route 
    - /operateur/configuration
    - /operateur/configuration/modifier
    - /operateur/configuration/creer
    - /operateur/configuration/supprimer
    - /operateur/configuration/list
- Model
    - creation du model prefixe
    - creation d'une fonction qui verifie le prefixe de l'operateur
        - quand prefixe n'appartient pas a operateur connecte, alors erreur 
    - creation des CRUD pour les prefixes
        - pour la modification de prefixe :
            - quand on modifie le prefixe, il faut updater tous les numeros des clients avec ce prefixes
- Controller
    - CRUD prefixe
- Vue
    - liste des prefixe avec des actions : modifer/supprimer
    - page de creation de prefixe

## Creation de types d'operations avec des barem de frais
- Route 
    - /operateur/operation
    - /operateur/operation/modifier
    - /operateur/operation/ajouter
    - /operateur/operation/list
    - /operateur/operation/supprimer
- MOdel
    - creation du model operation, type_operation, bareme_frais
    
