# Correctifs de SuiteCRM (`upstream-fixes`)

Corrige des bugs de SuiteCRM lui-même, en attendant qu'une version de SuiteCRM les
corrige : le changement de mot de passe demandé à la première connexion s'enregistre, et
les réglages d'administration rangés dans la table `config` (le nom du système, par
exemple) sont enfin sauvegardés.

| Fiche | |
|---|---|
| Version de SuiteCRM | 8.10 seulement (`>=8.10 <8.11`) |
| Types | `logic-hook` |
| Dépend de | — |
| Installée d'office | **oui**, sur toute nouvelle instance (`required-extensions` de suitecrm-core) |

## Installer

Rien à faire : suitecrm-core l'installe d'office sur toute nouvelle instance. Elle apparaît
ensuite dans **Administration → Chargeur de module** comme les autres, et se met à jour avec
l'image. Plusieurs extensions comptent sur elle (pwa, opportunity-reminders) : ne pas la
désinstaller.

## Documentation

| Page | Contenu |
|---|---|
| [Utilisation](docs/utilisation.md) | les bugs corrigés, vus par l'utilisateur |
| [Fonctionnement](docs/fonctionnement.md) | chaque correctif, son hook, quand le retirer |
| [Développement](docs/developpement.md) | ajouter un correctif, suivre les versions de SuiteCRM, vérifier, publier |

Les commandes, la CI et le format des extensions sont décrits dans la documentation de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation).
