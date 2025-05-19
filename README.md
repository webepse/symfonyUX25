# Préparation de l'app pour Symfony UX
## Création des entity
### User
-- make:user
* firstName
* lastName

### Team
* name (string)
* logo (string)

### Player
* firstName (string)
* lastName (string)
* number (integer)
* birthday (date)
* picture (string)
* team (relation Team ManyToOne)

## installation fixtures
```composer require --dev orm-fixtures```

```composer require fakerphp/faker```

## installation sécurité
```composer require symfony/rate-limiter```