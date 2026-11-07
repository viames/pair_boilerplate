# Releasing the Pair 4 starter

Starter **4.0.0** requires **Pair ^4.1.12**. The previous stable starter 3.x
belongs to the Pair 3 line. Tests use local artifact versions and never publish
packages or tags.

## Why this release

The Pair 4 starter ships explicit controllers/page state, authentication, ACL,
migrations and a fresh installer. It declares the actual PHP/extensions and
requires the corrected Composer binary bootstrap. Its schema and seed include
the current passkey table, Pair 4 `super` user field and baseline markers
for both Pair and application migrations. Guest login checks loaded-user state;
list pages format login dates and the Self test uses current runtime APIs. Fresh installation refuses a
populated database and generates the administrator password with a cryptographic
random source.

The template does not ship `composer.lock`: creating a new application resolves
compatible stable dependencies and writes that application's own lock. Commit
that generated lock in the application repository. Existing applications keep
their own lock; this release does not migrate or update them.

## Verify candidates before publication

Use sibling checkouts of `viames/pair` and `viames/pair_boilerplate`. Install the
framework's dev dependencies, then run from the framework:

```sh
composer test
composer test:install -- ../pair_boilerplate
```

Or run `php tests/install.php` from the starter. Set `PAIR_FRAMEWORK_PATH` if the
framework is not in the sibling `pair` directory. See the framework's
[installation test guide](https://github.com/viames/pair/tree/main/tests/Integration/Composer)
for Composer/zip requirements, offline artifact resolution and diagnostics.

For the fresh-database gate, provision an isolated MySQL instance or empty
`pair_test_...` schema, give a dedicated account grants only on that schema and
supply `PAIR_INSTALL_DB_HOST`, `PAIR_INSTALL_DB_NAME`, `PAIR_INSTALL_DB_USER` and
`PAIR_INSTALL_DB_PASS`. CI tests PHP 8.4/8.5 with MySQL 8.0, 8.4 and 26.10.
The test refuses populated schemas and cleans only the schema it claimed empty.
Never pass application credentials. The installer/unit tests do not replace
manual acceptance of browser login, authorization and the Self test module.

The installation test labels local archives with candidate versions and uses an
offline artifact repository. It runs actual `composer create-project` without
`dev-main`, checks both CLI entrypoints and renders the first installer page.
That is candidate evidence, not proof of the public Packagist installation path.

## Publication order

1. Release Pair 4.1.12 first, following its `RELEASING.md`. Verify stable Composer
   resolution and the generated `vendor/bin/pair` from that published release.
2. Verify the starter diff and test matrix. Keep `.env`, vendor trees, temporary
   data and any locally generated template lock out of the release commit.
3. Commit the prepared starter, create a new immutable annotated `4.0.0` tag,
   push the commit/tag and publish its GitHub Release. Never move the old 3.x tag.
4. Verify the new version appears in Packagist, then run from a clean directory:

   ```sh
   composer create-project viames/pair_boilerplate my-project
   cd my-project
   composer show viames/pair
   vendor/bin/pair make:module orders
   ```

5. Complete the browser installer against a disposable database. Verify login,
   generated password handling, a protected page and the Self test module.
6. Update the framework README and wiki quick starts to the verified stable
   command, removing the temporary `dev-main`/second-require workaround.

Publishing these packages does not authorize a consuming application's commit,
deployment or dependency update. Rollback of an existing application follows
its previous lock and release process. For a broken published starter, publish
a new patch tag rather than changing an existing release.
