# Instrukcijos
Prieš pradedant reikia:

 - Susiinstaliuoti [Node.js](https://nodejs.org/en/download/)
 - Susiinstaliuoti [Yarn](https://yarnpkg.com/lang/en/docs/install/)

 Nusiklonavus repozitoriją, į command line įrašom:
 

 - `composer install`
 - `yarn install`
 - `php bin/console doctrine:database:create`
 - `php bin/console doctrine:migrations:migrate`
 - `php bin/console doctrine:fixtures:load`

Ką mes turim:

Symfony su šiais bundles:

 - Server (galim pasileisti serverį per command line 
 `bin/console server:run`
 - Annotations (visokios anotacijos, įskaitant ir @Route - 
 galėsim route'us turėt pasirašę anotacijose)
 - Doctrine (darbui su duomenų baze)
 - Twig (template'inimui)
 
 Webpack Encore:
 
 - Leidžia sujungti turimus .js ir .css failus į vieną
 - SASS loaderis
 - Bootstrap
 - jQuery
 - React
 
 ### Papildomų bibliotekų instaliavimas
 Symfony:
 `composer require x`
 Webpack:
 `yarn add x`
### "Sukompiliavimas"
Norint "sukompiliuoti" turimus .js failus į vieną:
`yarn run encore dev`
Jeigu norima, kad "kompiliavimas" vyktų "gyvai", t.y., 
pakeičiam kodą ir iškart persibuild'ina:
`yarn run encore dev --watch`

### Paleidimas
`bin/console server:run`
Tada einam į nurodytą konsolėje adresą (127.0.0.1:8000)

### Kitos pastabos
Šiuo metu webpack sukonfigūruotas taip, kad "klausytųsi"
pakeitimų assets/js/app.js faile, ir sugeneruotų public/build/js/app.js failą. Jeigu norim, kad "klausytųsi" daugiau failų, pridedam į webpack.config.js failą papildomų eilučių:
`.addEntry('sukompiliuotiFailoPavadinimas', 'keliasIkiKlausomoFailo')`

Čia šiaip labai neblogai galima pasižiūrėti apie visą Webpack Encore logiką:
https://knpuniversity.com/screencast/webpack-encore

