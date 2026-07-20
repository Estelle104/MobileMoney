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


### (...) Situation des comptes clients

- Route
  - () GET '/operateur/clients/list'
  - () GET '/operateur/clients/detail/(:num)'
- Model
  - () 'ClientModel::getAllByOperateur(id_operateur)' (jointure via 'prefixe')
  - () 'ClientModel::getSoldeById(id_client)'
  - () 'OperationModel::getHistoriqueByClient(id_client)'
- Controller
  - () 'list()' : liste des clients de l'operateur avec leur solde
  - () 'detail($id)' : detail d'un client + historique de ses operations (verifier qu'il appartient bien a l'operateur connecte)
- Vue
  - () Tableau des clients (numero, solde)
  - () Page detail avec historique des operations (type, montant, frais, date)



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
  - () Model : 'BaremeFraisModel::getFraisParMontant(id_type_operation=depot, montant)'
  - () Controller :
    - () Calcule le frais (peut etre 0 selon le bareme  pour "depot")
    - () Cree l'enregistrement dans 'operation' ('id_client_source' = client, 'id_client_destinataire' = NULL)
    - () Met a jour 'client.solde += montant'
  - () Vue : formulaire montant + confirmation

- Faire un retrait 
  - () Route GET '/client/retrait' + POST '/client/retrait/valider'
  - () Model : 'getFraisParMontant(id_type_operation=retrait, montant)'
  - () Controller :
    - () Verifie que 'solde >= montant + frais' (sinon erreur "solde insuffisant")
    - () Cree l'enregistrement 'operation'
    - () Met a jour 'client.solde -= (montant + frais)'
  - () Vue : formulaire montant + confirmation + affichage du frais avant validation

- Faire un transfert
  - () Route GET '/client/transfert' (formulaire) + POST '/client/transfert/valider'
  - () Model : 'getFraisParMontant(id_type_operation=transfert, montant)' + 'ClientModel::findByNumero(numero_destinataire)'
  - () Controller :
    - () Verifie que le numero destinataire existe (sinon erreur)
    - () Verifie que 'solde_source >= montant + frais'
    - () Cree l'enregistrement 'operation' ('id_client_source', 'id_client_destinataire' renseignes)
    - () Met a jour les deux soldes : 'source -= (montant + frais)', 'destinataire += montant'
    - () Idealement dans une transaction SQLite ('$db->transStart() / transComplete()') pour garantir la coherence
  - () Vue : formulaire (numero destinataire + montant), affichage du frais avant validation

- Voir les historiques
  - () Route GET '/client/historique'
  - () Model : 'OperationModel::getHistoriqueByClient(id_client)' (depots, retraits, transferts envoyes/reçus)
  - () Controller : recupere et trie par date decroissante
  - () Vue : tableau (date, type, montant, frais, sens pour les transferts : envoye/reçu)

