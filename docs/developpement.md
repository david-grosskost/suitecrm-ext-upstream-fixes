# Développement

Les commandes `make`, la CI et le format des extensions viennent de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation), à la
version de `composer.json`. Cette page ne décrit que ce qui est propre à l'extension.

## Ajouter un correctif

1. Reproduire le bug sur une instance locale de suitecrm-core, et noter la version de
   SuiteCRM.
2. Écrire la logique dans une classe de `legacy/include/upstream-fixes/`, avec en tête : le
   bug, son effet, ce que fait le correctif, et **quand le retirer**.
3. La brancher par un hook dans `legacy/Extension/…` (sans logique dans le hook).
4. Ajouter son test dans `tests/`, la ligne de [Utilisation](utilisation.md) et la section
   de [Fonctionnement](fonctionnement.md).

Un correctif ne modifie jamais un fichier de SuiteCRM : il agit avant ou après lui, par les
points d'extension prévus (hooks, surcharges dans `custom/`).

## Nouvelle version de SuiteCRM

La contrainte `suitecrm` de `extension.json` est volontairement étroite (`>=8.10 <8.11`) :
le build de l'image de suitecrm-core refuse l'extension sur une autre version. À chaque
nouvelle version mineure :

1. pour chaque correctif, vérifier si le bug existe encore ;
2. retirer ceux devenus inutiles (classe, hook, test, doc) ;
3. élargir la contrainte.

## Vérifier

```bash
make extension@validate && make quality@lint && make quality@stan && make test@unit
make package@build && make package@scan
```

## Tester sur une instance locale

1. `make package@build`, puis sur l'instance locale de suitecrm-core, **Administration →
   Chargeur de module** : **Choisir un fichier** (`build/upstream-fixes-<version>.zip`),
   **Télécharger**, **Installer** (la nouvelle version remplace l'ancienne).
2. Reproduire le bug corrigé : il ne doit plus apparaître.
3. Dans suitecrm-core : `make test@regression`.

## Publier une version

Pull request relue, puis merge (squash) : release-please ouvre la pull request
« chore(main): release x.y.z ». La merger crée le tag, la release et son paquet.
suitecrm-core la récupère avec
`make composer@update PACKAGES="david-grosskost/suitecrm-ext-upstream-fixes"`.

Mettre à jour `README.md` et `docs/` dans la même pull request que le changement qu'ils
décrivent.
