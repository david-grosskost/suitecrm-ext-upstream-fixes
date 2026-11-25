# Utilisation

## Ce qui change

| Sans le correctif | Avec |
|---|---|
| À la première connexion (ou après une réinitialisation), le nouveau mot de passe ne s'enregistre pas : « Aucune action avec ce nom » | le changement de mot de passe s'enregistre |
| Les réglages d'**Administration → Paramètres système** rangés dans la table `config` ne s'enregistrent jamais : le « Nom du système » reste « SuiteCRM », dans l'onglet du navigateur comme dans le nom de l'application installable | ces réglages sont enregistrés |

## Réglages

Aucun.

## Données de démo

Aucune.

## Limites

Ne corrige que les bugs listés ici. Un autre bug de SuiteCRM se corrige en ajoutant un
correctif (voir [Développement](developpement.md)).

Le « Back To Top » du pied de page de SuiteCRM 8 reste en anglais : il est écrit en dur dans
son template Angular. Un correctif qui le rendra traduisible est prévu.
