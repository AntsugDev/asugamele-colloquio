# Test colloquio 24-09

## Linguaggio e versioni

- laravel 11
- php 8.2
- node 20.11
- npm 10.2.4
- vue 3


## Librerie aggiuntive laravel

- laravel/passport
- darkaonline/l5-swagger

## Database

- mysql

### Dati di accesso al database

<strong>USERNAME</strong>: usersql<br />
<strong>PASSWORD</strong>: 123qwe!@<br />
<strong>DB_NAME</strong>: laravel_test<br />
<strong>DB_PORT</strong>: 3306<br />

Il database l'ho inserito nel container del docker, come spiegato sotto.

In progetti reali non di sviluppo, dal mio punto di vista è sempre meglio tenerli separti container applicazione e database.

***

## Swagger

É stato aggiunto lo swagger, per la documentazione delle api presente all'indirizzo:
[Swagger]({host}/swagger/documentation)

***

## Docker
Ho creato un container, che contiene l'applicativo e un db mysql.

Una volta creato e avviato il container vanno eseguiti i seguenti commandi:

- migrate:
  <pre>docker exec Application  php artisan migrate</pre>
  oppure
  <pre>docker exec Application  php artisan migrate --seed</pre>
  oppure  
  <pre>docker exec Application  php artisan migrate:fresh --seed</pre>
- <pre>docker exec Application  php artisan passport:install</pre>
- <pre>docker exec Application  php artisan schedule:work</pre>

Ho creato anche un commando, per verificare se il database sia stato creato e l'applicativo sia connesso ad esso.

Il comando da eseguire è:

- <pre>docker exec Application  php artisan test-connect</pre>

***

## Envorement

Per le variabili d'ambiente, rinominare il file [.env.example](.env.example) in <i>.env</i>

***

## Test case

Nel file [tests/Feature/AuthTest.php](AuthTest.php), vengono testate tutte le api in questa sequenza:
1. login
2. refresh token
3. lista dati
4. logout

Per poter testare tale funzionalità, si può eseguire il seguente comando:

- <pre>docker exec Application  php artisan test --testsuite=Feature</pre>

***

## Struttura del progetto

### Back-end

Questa parte conta delle seguenti api:

1. login 
2. refresh token
3. logout
4. lettura dati derivanti dall'api [https://api.openbrewerydb.org/v1/breweries](https://api.openbrewerydb.org/v1/breweries)

#### Login

La gestione dell'auth si base sulle funzionalità presenti in passport.
La base dei dati è la tabella <i>users</i>, gestita attraverso il model user(<a href="app/Models/User.php">Model</a>).
Il controller per la gestione dell'api è [app/Http/Api/User/UserController.php](UserController.php@login).<br />
In questa funzione, dapprima si verifica la presenza dell'utente attraverso la classe <i>Auth</i>; 
se le credenziali sono corrette, si verifica se ci sono token attivi per questa utenza e se ci fossero, viene impostato il campo  revoked della tabella <i>oauth_access_tokens</i> a true. <br />
Inoltre, viene modificato nella tabella <i>oauth_access_tokens</i> l'expired di del token(*).<br />
Viene creato il refresh token.
Tutto questo, viene restitutto come risposta dell'api insieme ai dati dell'utente.<br />

Di default sono state impostate le seguenti utenze, create attraverso il seeder [database/seeders/UserSeeder.php](UserSeeder.php)

<table>
<thead>
<tr>
<th>Email</th>
<th>Password</th>
</tr>
</thead>
<tbody>
<tr>
<td>root@root.it</td>
<td>root@123</td>
</tr>
<tr>
<td>admin@admin.it</td>
<td>admin@123</td>
</tr>
</tbody>
</table>

<i style="font-size:12px">*: La creazione della tupla sulla tabella <strong>oauth_clients</strong>, avviene attraverso un booted presente nel model User.</i>

#### Refresh token

Questa sezione viene gestita nel controller [app/Http/Api/User/UserController.php](UserController.php@refresh). 

Viene passato il valore del resfresh token, derivante dall'api precedente.
Viene verificato:
- se il refresh token è corretto
- se il refresh token non è scaduto <br />
Se entrambe le condizioni sono verificate, il sistema imposta il campo revoked della tabella <i>oauth_clients</i> a true, revoca il refresh token e crea un nuovo token con le stesse modalità della login.
La response di questa api, sarà uguale alla response dell'api precedente.

#### Logout
Questa sezione viene gestita nel controller [app/Http/Api/User/UserController.php](UserController.php@logout).

In questa funzione viene revocato il token.
Se non si sono verificate eccezioni, il sistema ritorna lo stato 200.

#### Lettura dei dati

Questa sezione viene gestita nel controller [app/Http/Api/ApiController/ApiController.php](ApiController.php@get_list).

La funzione istanzia un nuovo oggetto di <i>GuzzleHttp\Client</i>  e chiama l'api https://api.openbrewerydb.org/v1/breweries in get.

Se questa risponde positivamente, viene restitua la risposta all'interno di un JsonResponse, altrtimenti viene restituita l'eccezione.

La lista di ritorno sarà impaginata secondo la segunte regola:
- nr.elementi richiesti 10
- nr. pagina 1
Questi sono i valori di default impostati alle variabili size(nr.elementi) e page(nr.page) in query string. Ad ogni chiamata, questi valori possono essere cambiati.  


#### Note

Il token generato ha validità un'ora; esso è legato allas chiave secreta presente sulla tabella <i>oauth_clients</i>, per quel utente.

Il sistema ogni mezz'ora schedulerà un commando, che andrà a cambiare questa chiave e, se presenti, andrà ad revocare i token(presenti sulla tabella <i>oauth_access_tokens</i>), creati da più di due ore e non revocati.

Così facendo, il token che si sta utilizzando non sarà più buono.

Sempre lato backe-end, nella situazione in cui o si cerca di chiamare un url non presente oppure se ci fossero problemi derivanti dalla connessione al db, il sistema reivierà l'utente alla pagina di errore gestita nelle viste di laravel ([resources/views/error.blade.php](error)).

La gestione delle eccezione è stata gestita direttamente nella sezione presente nel file [bootstrap/app.php](app.php).

---


### Front-end

La parte fe è stata gestitra attraverso il framework vue, vuetify per i componenti, vuerouter per la gestione delle rotte e vuex per la gestione dei dati.
Questa è stata inizializzata nella directory <i>resources</i>.<br />

Si divide:

1. Pagina Login
2. Pagina Table
3. Pagina gestione utenza:
   - refresh token
   - view token/refresh token (rilasciato solo per test)
   - logout

Le chiamate api sono state realizate attraverso <i>axios</i>, creando una funzione generica che gestisce direttamente la chiamata ritornando una Promise e, sempre in questa funzione viene gestito in automatico(in base all'url dell'api che viene chiamato), se passare nella request header il bearer token.

In fase di login, se l'api risponde positivamente, il sistema inserisce nel vuex sia il token, sia il refresh token; nonchè, prima di procedere, il sistema verifica la presenza o meno del token e se questo non è scaduto. Se non fosse presente o scaduto, il sistema rimanderà l'utente alla pagina di login.

La possibilità di refresh token, all'interno della pagina di gestione utenza, è puramente a scopo di test e in un progetto reale, gestirei la chiamata o al cambiamento di pagina, oppure ad intervalli regolari, se il token ha una validità inferiore ad un'ora. 

Ho tralasciato un pò la parte grafica, usando semplicemnte i componenti messi a disposizione da vuetify così come sono.

***



