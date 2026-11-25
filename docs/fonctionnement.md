# Fonctionnement

Chaque correctif est un hook `after_entry_point` de SuiteCRM (lancé une fois SuiteCRM
chargé, avant qu'il traite la requête) qui appelle une classe testée sans SuiteCRM.

## Fichiers

| Fichier | Rôle |
|---|---|
| `legacy/Extension/application/Ext/LogicHooks/upstream-fixes.php` | déclare les hooks, dans l'ordre |
| `legacy/include/upstream-fixes/EmptyEntryPoint.php` | correctif du changement de mot de passe |
| `legacy/include/upstream-fixes/PostedSettings.php` | correctif des réglages d'administration |
| `tests/` | un test par correctif |

## Changement de mot de passe (`EmptyEntryPoint`)

Le formulaire de changement de mot de passe (`modules/Users/Changenewpassword.tpl`) envoie
toujours un champ `entryPoint`, vide en dehors du lien « mot de passe oublié ». SuiteCRM
refuse alors la requête (« Aucune action avec ce nom »). Un `entryPoint` vide signifie
« pas de point d'entrée » : il est retiré de la requête avant que SuiteCRM la lise.

**À retirer** quand une version de SuiteCRM n'envoie plus de champ vide.

## Réglages d'administration (`PostedSettings`)

Depuis SuiteCRM 8, `Administration::saveConfig()` lit `$_GET` (SuiteCRM 7 lisait `$_POST`),
alors que les formulaires d'administration envoient leurs valeurs en `POST`. Le correctif
recopie dans `$_GET` les réglages postés de ces catégories (`system_…`, `notify_…`,
`proxy_…`…), avant que SuiteCRM traite la requête.

Il se limite à ce qu'il corrige : un `POST` vers un module dont les pages de réglages
appellent `saveConfig()`, et seulement les réglages que l'adresse ne porte pas déjà. Qui
peut enregistrer reste vérifié par SuiteCRM dans ces pages (administrateurs seulement).

**À retirer** quand une version de SuiteCRM lit de nouveau `$_POST`.

## Données en base

Aucune donnée propre : le second correctif permet seulement à SuiteCRM d'écrire ses propres
réglages dans la table `config`.

## Installation et désinstallation

Pas de script. Installée d'office par suitecrm-core sur une nouvelle instance, puis traitée
comme les autres extensions dans le Chargeur de module.

## Points d'attention

- SuiteCRM appelle ces hooks avec des arguments `null` : leurs méthodes acceptent `mixed`. Un
  type plus strict arrêterait SuiteCRM à chaque requête.
- Compatible avec SuiteCRM 8.10 **seulement** : chaque correctif vise un bug d'une version
  précise (voir [Développement](developpement.md#nouvelle-version-de-suitecrm)).
