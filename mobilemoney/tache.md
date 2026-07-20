# Mobile Money

## Initialisation du projet

- (ok) (etu004185) Creation du depot github 
- (ok) (etu004185) Ecriture du todo
- (ok) (etu004219) Mise en place du template CodeIgniter 
- (ok) (etu004185, etu004219) Conception de la base de donnees 
  - (ok) (etu004219) ecriture des migrations CI4 
  - (ok) (etu004219) Seeder 

---

## Routage

- (ok) Creer les routes et les proteger avec un filtre CI4 (etu004185)
  - (ok) Groupe '/operateur' protege par 'AuthOperateurFilter' (verifie session 'operateur_id')
  - (ok) Groupe '/client' protege par 'AuthClientFilter' (verifie session 'client_id')
  - (ok) Routes publiques : '/operateur/login', '/client/login'
- (ok) Definir la structure des routes dans 'app/Config/Routes.php' avec groupes de routes

---

## Operateur (etu004185)

### (ok) Login Operateur

- Route
  - (ok) GET '/operateur/login'
  - (ok) POST '/operateur/checklogin'
  - (ok) GET '/operateur/logout'

- Model (OperateurModel)
  - (ok) Champs geres : 'nom', 'email', 'mdp'
  - (ok) Methode 'verifIdentifiants(email, mdp)' avec 'password_verify()'

- Controller
  - (ok) 'checkLogin(email, mdp)' : verifie via le Model, stocke 'operateur_id' en session si OK
  - (ok) Si echec -> message d'erreur + redirection '/operateur/login'
  - (ok) Si deja connecte et acces a '/operateur/login' -> redirection vers '/operateur/dashboard'

- Vue
  - (ok) '/operateur/login' : formulaire email/mdp + affichage flashdata erreur

### (ok) Configuration des prefixes

- Route
  - (ok) GET '/operateur/configuration/list'
  - (ok) GET '/operateur/configuration/creer'
  - (ok) POST '/operateur/configuration/enregistrer'
  - (ok) GET '/operateur/configuration/modifier/(:num)'
  - (ok) POST '/operateur/configuration/mettreajour/(:num)'
  - (ok) POST '/operateur/configuration/supprimer/(:num)'

- Model ('PrefixeModel')
  - (ok) Validation : 'code' exactement 3 chiffres, unique globalement
  - (ok) CRUD  et 'findAllByOperateur(id_operateur)'
  - (ok) Lors de la modification d'un 'code' : mettre a jour tous les 'client.numero' avec ce code
  - (ok) Avant suppression : verifier qu'aucun 'client' n'est rattache a ce prefixe  (confiramtion)

- Controller
  - (ok) 'list()' : liste des prefixes de l'operateur connecte
  - (ok) 'creer()' / 'enregistrer()' : validation formulaire + insertion (forcer 'id_operateur' = session, pas depuis le formulaire)
  - (ok) 'modifier($id)' / 'update($id)' : verifie que le prefixe appartient bien a l'operateur connecte avant update
  - (ok) 'supprimer($id)' : meme verification avant suppression

- Vue
  - (ok) Liste des prefixes (tableau) avec boutons Modifier/Supprimer + confirmation
  - (ok) Formulaire de creation/modification 


### (ok) Types d'operations et bareme de frais

- Route
  - (ok) GET '/operateur/operation/list'
  - (ok) GET '/operateur/operation/ajouter'
  - (ok) POST '/operateur/operation/enregistrer'
  - (ok) GET '/operateur/operation/modifier/(:num)'
  - (ok) POST '/operateur/operation/update/(:num)'
  - (ok) POST '/operateur/operation/supprimer/(:num)'

- Model
  - (ok) 'TypeOperationModel' : lecture des 3 types fixes (depot, retrait, transfert)
  - (ok) 'BaremeFraisModel' :
    - (ok) Validation : 'montant_min < montant_max', 'frais >= 0'
    - (ok) Verifier l'absence de chevauchement de tranches pour un meme 'id_type_operation'
    - (ok) Methode 'getFraisParMontant(id_type_operation, montant)' -> parcourt les tranches et retourne le frais applicable
    - () Gerer le cas d'un montant hors de toutes les tranches (erreur metier claire)
    - (ok) CRUD complet pour les tranches

- Controller
  - (ok) 'list($id_type_operation)' : affiche le bareme pour un type d'operation donne
  - (ok) 'ajouter()' / 'enregistrer()' : ajout d'une tranche avec controle de chevauchement
  - (ok) 'modifier($id)' / 'update($id)' : modification d'une tranche existante
  - (ok) 'supprimer($id)' : suppression d'une tranche
- Vue
  - (ok) Tableau des tranches par type d'operation (montant_min – montant_max – frais)
  - (ok) Formulaire d'ajout/modification (avec dropdown de type d'operation)

### (ok) Situation des gains via les frais (retrait et transfert)

- Route
  - (ok) GET '/operateur/gains'
  - (ok) GET '/operateur/gains/filtrer'

- Model
  - (ok) Methode dans 'OperationModel' : 'getTotalFraisParType(date_debut, date_fin)'
  - (ok) Requete agregee 'SUM(frais)' groupee par 'id_type_operation', restreinte aux clients dont le prefixe appartient a l'operateur connecte (jointure 'client' -> 'prefixe' -> 'operateur')

- Controller
  - (ok) 'index()' : affiche le total des gains (retrait + transfert), depot exclu si sans frais
  - (ok) 'filtrer()' : applique un filtre par plage de dates (formulaire GET)
  
- Vue
  - (ok) Dashboard avec total gains retrait / total gains transfert / total general
  - (ok) Formulaire de filtre par date


### (ok) Situation des comptes clients

- Route
  - (ok) GET '/operateur/clients/list'
  - (ok) GET '/operateur/clients/detail/(:num)'
  
- Model
  - (ok) 'ClientModel::getAllByOperateur(id_operateur)' (jointure via 'prefixe')
  - (ok) 'ClientModel::getSoldeById(id_client)'
  - (ok) 'OperationModel::getHistoriqueByClient(id_client)'

- Controller
  - (ok) 'list()' : liste des clients de l'operateur avec leur solde
  - (ok) 'detail($id)' : detail d'un client + historique de ses operations (verifier qu'il appartient bien a l'operateur connecte)

- Vue
  - (ok) Tableau des clients (numero, solde)
  - (ok) Page detail avec historique des operations (type, montant, frais, date)



## Client (etu004219)

### Login automatique par numero de telephone

- Route
  - (ok) GET '/client/login'
  - (ok) POST '/client/checklogin'
  - (ok) GET '/client/logout'

- Model ('ClientModel')
  - (ok) Methode 'trouverOuCreerParNumero(numero)' :
    - (ok) Extrait les 3 premiers chiffres du numero saisi
    - (ok) Cherche le 'prefixe' correspondant dans 'prefixe.code'
    - (ok) Si aucun prefixe ne correspond -> erreur
    - (ok) Si le 'client.numero' existe deja -> le retourne
    - (ok) Sinon -> cree le client avec 'solde = 0.00' et le retourne

- Controller
  - (ok) 'checkLogin(numero)' : appelle 'trouverOuCreerParNumero()', stocke 'client_id' en session
  - (ok) Redirection vers '/client/dashboard' apres login
  - () Si numero invalide (format ou prefixe inconnu) -> message d'erreur + retour '/client/login'
- Vue
  - (ok) '/client/login' : simple formulaire avec champ "numero de telephone"
  - () Validation JS/serveur basique du format du numero

### Operations

- Voir le solde
  - (ok) Route GET '/client/solde'
  - (ok) Controller : lit 'client.solde' du client en session
  - (ok) Vue : affichage simple du solde courant

- Faire un depot
  - (ok) Route GET '/client/depot' (formulaire) + POST '/client/depot/valider'
  - (ok) Model : 'BaremeFraisModel::getFraisParMontant(id_type_operation=depot, montant)'
  - (ok) Controller :
    - (ok) Calcule le frais (peut etre 0 selon le bareme  pour "depot")
    - (ok) Cree l'enregistrement dans 'operation' ('id_client_source' = client, 'id_client_destinataire' = NULL)
    - (ok) Met a jour 'client.solde += montant'
  - (en cours) Vue : formulaire montant + confirmation

- Faire un retrait 
  - (ok) Route GET '/client/retrait' + POST '/client/retrait/valider'
  - (ok) Model : 'getFraisParMontant(id_type_operation=retrait, montant)'
  - (ok) Controller :
    - (ok) Verifie que 'solde >= montant + frais' (sinon erreur "solde insuffisant")
    - (ok) Cree l'enregistrement 'operation'
    - (ok) Met a jour 'client.solde -= (montant + frais)'
  - () Vue : formulaire montant + confirmation + affichage du frais avant validation

- Faire un transfert
  - (ok) Route GET '/client/transfert' (formulaire) + POST '/client/transfert/valider'
  - (ok) Model : 'getFraisParMontant(id_type_operation=transfert, montant)' + 'ClientModel::findByNumero(numero_destinataire)'
  - (ok) Controller :
    - (ok) Verifie que le numero destinataire existe (sinon erreur)
    - (ok) Verifie que 'solde_source >= montant + frais'
    - (ok) Cree l'enregistrement 'operation' ('id_client_source', 'id_client_destinataire' renseignes)
    - (ok) Met a jour les deux soldes : 'source -= (montant + frais)', 'destinataire += montant'
    - (ok) Idealement dans une transaction SQLite ('$db->transStart() / transComplete()') pour garantir la coherence
  - () Vue : formulaire (numero destinataire + montant), affichage du frais avant validation

- Voir les historiques
  - (ok) Route GET '/client/historique'
  - (ok) Model : 'OperationModel::getHistoriqueByClient(id_client)' (depots, retraits, transferts envoyes/reçus)
  - (ok) Controller : recupere et trie par date decroissante
  - (ok) Vue : tableau (date, type, montant, frais, sens pour les transferts : envoye/reçu)


------------------------------------


# V2
## Version 2 — Côté opérateur (etu004185)

### () Configuration des préfixes des autres opérateurs

- Route
  - () GET '/operateur/prefixe-externe/list'
  - () GET '/operateur/prefixe-externe/creer'
  - () POST '/operateur/prefixe-externe/enregistrer'
  - () GET '/operateur/prefixe-externe/modifier/(:num)'
  - () POST '/operateur/prefixe-externe/mettreajour/(:num)'
  - () POST '/operateur/prefixe-externe/supprimer/(:num)'

- Model ('PrefixeExterneModel')
  - () Table 'prefixe_externe' : 'id', 'code' (3 chiffres), 'nom_operateur_externe', 'pourcentage_commission', 'id_operateur' (celui qui a saisi la config)
  - () Validation : 'code' exactement 3 chiffres, unique parmi 'prefixe' (interne) ET 'prefixe_externe' (pas de doublon entre les deux tables) ; 'pourcentage_commission >= 0'
  - () CRUD et 'findAllByOperateur(id_operateur)'
  - () Methode utilitaire 'estPrefixeExterne(code)' -> retourne la ligne (nom_operateur_externe + pourcentage_commission) si le code correspond, sinon false
  - () Avant suppression : verifier qu'aucune 'operation' n'a ce 'id_prefixe_externe' (ou juste avertir, a discuter)

- Controller
  - () 'list()' : liste des prefixes externes configures par l'operateur connecte
  - () 'creer()' / 'enregistrer()' : validation formulaire + insertion (forcer 'id_operateur' = session)
  - () 'modifier($id)' / 'update($id)' : verifie que le prefixe externe appartient bien a l'operateur connecte avant update
  - () 'supprimer($id)' : meme verification avant suppression

- Vue
  - () Liste des prefixes externes (tableau : code, nom operateur externe, % commission) avec boutons Modifier/Supprimer + confirmation
  - () Formulaire de creation/modification (ex: code=032, nom_operateur_externe="Orange Money", pourcentage_commission=1.5)

---

### () Commission supplementaire pour transferts vers les autres opérateurs

Note : le pourcentage n'est plus global par operateur, il est propre a chaque 'prefixe_externe' (deja saisi dans la section precedente), car il peut varier d'un concurrent a l'autre (ex: +1% vers Orange, +2% vers Airtel).

- Model
  - () Ajout des colonnes 'numero_destinataire_externe' (VARCHAR 20, nullable) et 'id_prefixe_externe' (INT, nullable, FK vers 'prefixe_externe') sur la table 'operation'
  - () Regle metier a valider avant insertion : soit 'id_client_destinataire' est rempli (transfert interne), soit 'numero_destinataire_externe' + 'id_prefixe_externe' le sont (transfert externe) — jamais les deux, jamais aucun des deux
  - () Modifier le calcul du frais lors d'un transfert : frais = 'BaremeFraisModel::getFraisParMontant()' (frais normal) + si externe, '+ montant * (pourcentage_commission / 100)' recupere via 'PrefixeExterneModel::estPrefixeExterne()'

- Controller
  - () Dans le controller de transfert (existant, V1) : detecter si le numero destinataire correspond a un client interne ou a un 'prefixe_externe' (via 'estPrefixeExterne()')
  - () Si externe : remplir 'numero_destinataire_externe' + 'id_prefixe_externe' au lieu de 'id_client_destinataire', et appliquer le frais majore
  - () Si le prefixe du numero destinataire n'est reconnu ni en interne ni en externe : erreur metier claire ("operateur destinataire inconnu")

- Vue
  - () Sur le formulaire de transfert (V1) : rien de visible a ajouter, la detection est automatique ; eventuellement afficher le frais total (avec majoration) en recapitulatif avant confirmation

---

### () Separer opérateur / autres opérateurs sur la page "Situation gains via les différents frais"

- Route
  - () Reutiliser '/operateur/gains' et '/operateur/gains/filtrer' (V1), ajouter parametre de vue

- Model
  - () Modifier 'OperationModel::getTotalFraisParType()' pour distinguer, via 'id_prefixe_externe' :
    - operations internes ('id_prefixe_externe' NULL, destinataire = client interne)
    - operations externes ('id_prefixe_externe' NOT NULL)
  - () Nouvelle methode 'getTotalFraisParTypeEtDestination(date_debut, date_fin)' -> retourne un tableau structure par (type_operation, interne/externe)

- Controller
  - () 'index()' : passer a la vue les deux jeux de totaux (interne / externe) au lieu d'un seul total global
  - () 'filtrer()' : idem avec le filtre de dates applique aux deux

- Vue
  - () Dashboard existant scinde en 2 blocs : "Transferts internes" et "Transferts vers autres opérateurs", chacun avec son propre total retrait/transfert
  - () Garder le total general en recapitulatif en bas de page

---

### () Situation des montants à envoyer à chaque opérateur

- Route
  - () GET '/operateur/reglements-externes'
  - () GET '/operateur/reglements-externes/filtrer'
  - () GET '/operateur/reglements-externes/creer'
  - () POST '/operateur/reglements-externes/enregistrer'

- Model
  - () Nouvelle methode 'OperationModel::getMontantsParOperateurExterne(date_debut, date_fin)' -> 'SUM(montant)' groupe par 'nom_operateur_externe' (jointure 'operation' -> 'prefixe_externe'), pour les transferts sortants uniquement
  - () 'ReglementExterneModel' : CRUD sur la table 'reglement_externe' ('id_operateur', 'nom_operateur_externe', 'montant', 'date_reglement')
  - () Methode 'ReglementExterneModel::getTotalReglePar(nom_operateur_externe, id_operateur)' -> 'SUM(montant)' des reglements deja enregistres
  - () Calcul du solde a payer = montant total transfere (via 'operation') - total deja regle (via 'reglement_externe'), par nom d'operateur externe

- Controller
  - () 'index()' : pour chaque operateur externe (distinct dans 'prefixe_externe' de l'operateur connecte), affiche montant total transfere, montant deja regle, solde restant a payer
  - () 'filtrer()' : filtre le montant total transfere par plage de dates
  - () 'creer()' / 'enregistrer()' : formulaire pour saisir un nouveau reglement effectue (nom_operateur_externe, montant), force 'id_operateur' = session

- Vue
  - () Tableau : Operateur externe | Montant total transfere | Montant deja regle | Solde a payer
  - () Formulaire de filtre par date
  - () Formulaire d'ajout d'un reglement (montant + operateur externe concerne)




## Cote client (etu004219)
# Faire un transfert (Même opérateur / Autre opérateur)

* Route

  * () GET '/client/transfert' (inchangé)
  * () POST '/client/transfert/valider' (à compléter)

* Model

  * () PrefixeModel::trouverParCode(code)
  * () PrefixeModel::estMemeOperateur(idPrefixeSource, idPrefixeDestination)
  * () ClientModel::getPrefixeClient(idClient)
  * () OperateurModel::getCommissionAutreOperateur()
  * (ok) BaremeFraisModel::getFraisParMontant(id_type_operation=transfert, montant)

* Controller (validerTransfert())

  * () Récupère le numéro destinataire
  * () Extrait les 3 premiers chiffres
  * () Récupère le préfixe du destinataire
  * () Récupère le préfixe du client connecté
  * () Vérifie si le transfert est vers le même opérateur

#### Si même opérateur

* () Calcule les frais de transfert
* () Vérifie si "Inclure les frais de retrait" est coché
* () Calcule les frais de retrait si nécessaire
* () Vérifie que solde >= montant + frais_transfert + frais_retrait
* () Crée l'opération
* () Débite le client
* () Créditer le destinataire

#### Si autre opérateur

* () Calcule les frais de transfert

* () Calcule la commission supplémentaire (%)

* () Vérifie que solde >= montant + frais_transfert + commission

* () Crée l'opération

* () Débite uniquement le client

* () Enregistre le montant à envoyer à l'autre opérateur

* () Ne crédite aucun client de la base

* Vue

  * () Afficher automatiquement si le numéro appartient au même opérateur
  * () Afficher/Masquer la case *"Inclure les frais de retrait"*
  * () Afficher les frais de transfert
  * () Afficher la commission (si autre opérateur)
  * () Afficher le total débité avant validation

---

## Option "Inclure les frais de retrait"

* Base de données

  * () Ajouter le champ frais_retrait_inclus dans la table operation

* Controller (validerTransfert())

  * () Lire la valeur de la case à cocher
  * () Enregistrer frais_retrait_inclus = 1 si cochée
  * () Ajouter les frais de retrait au montant débité

* Controller (validerRetrait())

  * () Vérifier si un transfert reçu possède frais_retrait_inclus = 1
  * () Si oui, supprimer les frais de retrait
  * () Remettre frais_retrait_inclus = 0 après utilisation

---

## Envoi multiple

* Route

  * () GET '/client/transfert-multiple'
  * () POST '/client/transfert-multiple/valider'

* Model

  * (ok) ClientModel::findByNumero(numero)
  * () ClientModel::getPrefixeClient(idClient)
  * () PrefixeModel::estMemeOperateur(idPrefixeSource,idPrefixeDestination)
  * (ok) BaremeFraisModel::getFraisParMontant(id_type_operation=transfert,montant)

* Controller

  * () transfertMultiple() : affiche le formulaire
  * () validerTransfertMultiple() :

    * () Récupère la liste des numéros
    * () Récupère le montant total
    * () Vérifie qu'il y a au moins deux numéros
    * () Vérifie qu'aucun numéro n'est vide
    * () Vérifie qu'il n'y a aucun doublon
    * () Vérifie que tous les numéros existent
    * () Vérifie qu'aucun numéro n'est celui du client connecté
    * () Vérifie que tous appartiennent au même opérateur
    * () Calcule le montant individuel (montant_total / nombre_destinataires)
    * () Calcule les frais de chaque transfert
    * () Calcule le montant total à débiter
    * () Vérifie que le solde est suffisant
    * () Lance une transaction SQLite (transStart())
    * () Crée une opération pour chaque destinataire
    * () Créditer chaque destinataire
    * () Débiter le client une seule fois
    * () Valide la transaction (transComplete())

* Vue

  * () Formulaire avec plusieurs numéros
  * () Champ montant total
  * () Affichage du montant individuel
  * () Affichage des frais
  * () Affichage du total débité avant validation

---

## Historique

* Base de données

  * () Ajouter le champ id_groupe_transfert dans la table operation

* Model (OperationModel)

  * () Adapter getHistoriqueByClient()
  * () Regrouper les opérations ayant le même id_groupe_transfert

* Controller

  * () Déterminer si une opération est un transfert multiple
  * () Envoyer cette information à la vue

* Vue

  * () Afficher *"Transfert multiple vers X destinataires"*
  * () Afficher le détail si nécessaire

---

## Nouveaux modèles / méthodes

### PrefixeModel

* () trouverParCode(code)
* () estMemeOperateur(idPrefixeSource, idPrefixeDestination)

### ClientModel

* (ok) findByNumero(numero)
* () getPrefixeClient(idClient)

### BaremeFraisModel

* (ok) getFraisParMontant(idTypeOperation, montant)

### OperateurModel

* () getCommissionAutreOperateur()
