# Correctifs de SuiteCRM (`upstream-fixes`)

Extension SuiteCRM qui corrige des bugs de SuiteCRM lui-même, en attendant qu'une
version de SuiteCRM les corrige. Active sur toutes les instances (variable `EXTENSIONS`
de suitecrm-core).

## Correctifs

| Bug | Effet sans le correctif | Correctif |
|---|---|---|
| Le formulaire de changement de mot de passe envoie un champ `entryPoint` vide | Le changement demandé à la première connexion (ou après une réinitialisation) ne s'enregistre pas : « Aucune action avec ce nom » | Hook `after_entry_point` : un `entryPoint` vide est retiré de la requête ([`EmptyEntryPoint`](legacy/include/upstream-fixes/EmptyEntryPoint.php)) |

## Version de SuiteCRM

Compatible SuiteCRM 8.10 seulement (`extension.json`) : à chaque nouvelle version
mineure de SuiteCRM, vérifier pour chaque correctif si le bug existe encore, retirer
ceux devenus inutiles, puis élargir la contrainte. Le build de l'image refuse
l'extension tant que ce n'est pas fait.

## Développer

```bash
make extension@validate   # format : manifeste, composer.json, place des fichiers
make quality@lint         # style du code, scripts
make quality@stan         # analyse statique (PHPStan, niveau max)
make test@unit            # tests unitaires (PHPUnit)
```

La logique vit dans des classes testées sans SuiteCRM (`legacy/include/upstream-fixes/`) ;
le hook (`legacy/Extension/…`) ne fait que les appeler. Les commandes, la CI et les règles
viennent de [suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit).
