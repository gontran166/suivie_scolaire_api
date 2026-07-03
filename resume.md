# Résumé du projet - Plateforme de gestion scolaire

## État du projet

Architecture backend Laravel avec :
- Models
- Resources
- Repositories
- Repository Interfaces
- Dependency Injection (Bindings)

Les Services, DTOs, FormRequests et Controllers restent à implémenter.

---

## Fonctionnalités métier validées

### Gestionnaire (Directeur)
Peut gérer :
- Utilisateurs (enseignants et parents)
- Classes
- Élèves
- Matières
- Paiements
- Annonces

### Enseignant responsable d'une classe
Peut gérer :
- Notes des élèves de sa classe
- Absences des élèves de sa classe

### Parent
Peut :
- Se connecter
- Consulter les informations de son enfant
- Consulter les notes
- Consulter les moyennes
- Consulter le classement
- Consulter les paiements
- Télécharger les reçus PDF
- Consulter les absences
- Consulter les annonces

---

## Tables du projet

### users
- id
- name
- email
- password
- role (gestionnaire, enseignant, parent)
- softDeletes

### classes
- nom
- niveau
- frais_scolarite
- annee_scolaire
- user_id (enseignant responsable)

### eleves
- nom
- prenom
- date_naissance
- photo
- nom_parent
- telephone_parent
- classe_id
- user_id (parent)

### matieres
- nom
- coefficient
- classe_id

### paiements
- eleve_id
- montant
- date_paiement
- recu_pdf
- observations

### notes
- eleve_id
- matiere_id
- note
- trimestre
- annee_scolaire

### absences
Correction apportée :
- ajout de date_absence

Champs :
- eleve_id
- date_absence
- motif
- statut

### annonces
Conception retenue :

- titre
- contenu
- type
- classe_id nullable
- active
- date_expiration
- softDeletes

Règle :
- classe_id = NULL => annonce générale
- classe_id renseigné => annonce spécifique à une classe

---

## Améliorations apportées aux modèles

### Classe
Relations :
- enseignant()
- eleves()
- matieres()
- annonces()

Méthodes :
- totalFraisAttendus()

Gestion :
- unicité du nom malgré soft delete

### Eleve

Corrections :
- ajout user_id dans fillable
- ajout relation parent()
- ajout absences dans cascadeDeletes

Relations :
- classe()
- parent()
- paiements()
- notes()
- absences()

Méthodes :
- totalPaye()
- resteAPayer()

Accessor :
- nomComplet

### Matiere
Relations :
- classe()
- notes()

### Note
Relations :
- eleve()
- matiere()

### Paiement
Relation :
- eleve()

### User

Relations :
- classes()
- eleves()

Helpers :
- isGestionnaire()
- isEnseignant()

Gestion :
- unicité email malgré soft delete

### Absence
Relation :
- eleve()

### Annonce
Relation :
- classe()

---

## Resources créées

- UserResource
- ClasseResource
- EleveResource
- MatiereResource
- PaiementResource
- NoteResource
- AbsenceResource
- AnnonceResource

Utilisation de whenLoaded() pour les relations.

---

## Repository Pattern

### BaseRepositoryInterface

Méthodes :
- paginate()
- find()
- findOrFail()
- create()
- update()
- delete()

### BaseRepository

Méthodes :
- paginate()
- find()
- findOrFail()
- create()
- update()
- delete()
- query()

### Repositories créés

- UserRepository
- ClasseRepository
- EleveRepository
- MatiereRepository
- NoteRepository
- PaiementRepository
- AbsenceRepository
- AnnonceRepository

### Interfaces créées

- UserRepositoryInterface
- ClasseRepositoryInterface
- EleveRepositoryInterface
- MatiereRepositoryInterface
- NoteRepositoryInterface
- PaiementRepositoryInterface
- AbsenceRepositoryInterface
- AnnonceRepositoryInterface

---

## Bindings effectués

Dans AppServiceProvider :

- UserRepositoryInterface => UserRepository
- ClasseRepositoryInterface => ClasseRepository
- EleveRepositoryInterface => EleveRepository
- MatiereRepositoryInterface => MatiereRepository
- NoteRepositoryInterface => NoteRepository
- PaiementRepositoryInterface => PaiementRepository
- AbsenceRepositoryInterface => AbsenceRepository
- AnnonceRepositoryInterface => AnnonceRepository

---

## Architecture retenue

Request HTTP
↓
Route
↓
Middleware
↓
Controller
↓
FormRequest
↓
DTO (si nécessaire)
↓
Service
↓
Repository
↓
Model
↓
Database
↓
Resource
↓
Response JSON

---

## Décision concernant les DTO

Décision retenue :
- DTO uniquement pour les opérations de création/modification.
- Pas de DTO inutile pour les lectures simples.

---

## Prochaine étape

À réaliser lors de la prochaine session :

### Services
Créer :
- UserService
- ClasseService
- EleveService
- MatiereService
- PaiementService
- NoteService
- AbsenceService
- AnnonceService

### Logique métier à implémenter

- Création des élèves
- Gestion des paiements
- Génération des reçus PDF
- Calcul du reste à payer
- Saisie des notes
- Calcul des moyennes trimestrielles
- Classement des élèves
- Tableau de bord financier
- Liste des impayés
- Publication des annonces
- Tableau de bord parent

Fin du résumé.
