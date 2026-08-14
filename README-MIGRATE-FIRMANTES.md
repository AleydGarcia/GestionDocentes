To persist selected firmantes for each Tramite run the new migration and migrate:

1. Run migration:

```bash
php artisan migrate
```

2. Existing tramites will have null `firmantes`. If you want to backfill, run a small tinker or script to set `firmantes` from session or other source.

Notes:
- Column `firmantes` is JSON and cast to array in the `Tramite` model.
- The UI will now preselect persisted firmantes after saving a tramite and when printing.
