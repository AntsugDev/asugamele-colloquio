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

<strong>USERNAME</strong>: asugamele<br />
<strong>PASSWORD</strong>: 123qwe!@<br />
<strong>DB_NAME</strong>: asugamele_laravel<br />

***

## Swagger

É stato aggiunto lo swagger, per la documentazione delle api presente all'indirizzo:
<a href="/swagger/documentation">Swagger</a>

***

## Struttura del progetto

### Back-end

Questa parte conta di due api:

1. login 
2. refresh token
3. logout
4. lettura dati derivanti dall'api <a href="https://api.openbrewerydb.org/v1/breweries">https://api.openbrewerydb.org/v1/breweries</a>

#### Login

La gestione dell'auth si base sulle funzionalità presenti in passport.
La base dei dati è la tabella <i>users</i>, gestita attraverso il model user(<a href="app/Models/User.php">Model</a>).
Il controller per la gestione dell'api è <a href="app/Http/Api/User/UserController.php@login">UserController.php@login</a>.<br />
In questa funzione, dapprima si verifica la presenza dell'utente attraverso la classe <i>Auth</i>; 
se le credenziali sono corrette, si verifica se ci sono token attivi per questa utenza e se ci fossero, viene impostato il campo  revoked della tabella <i>oauth_access_tokens</i> a true. <br />
Inoltre, viene modificato nella tabella <i>oauth_access_tokens</i> l'expired di del token(*).<br />
Viene creato il refresh token.
Tutto questo, viene restitutto come risposta dell'api insieme ai dati dell'utente.<br />

Di default sono state impostate le seguenti utenze, create attraverso il seeder <a href="database/seeders/UserSeeder.php">UserSeeder.php</a><br />
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

Questa sezione viene gestita nel controller <a href="app/Http/Api/User/UserController.php@refresh">UserController.php@refresh</a>. <br/>
Viene passato il valore del resfresh token, derivante dall'api precedente.
Viene verificato:
- se il refresh token è corretto
- se il refresh token non è scaduto <br />
Se entrambe le condizioni sono verificate, il sistema imposta il campo revoked della tabella <i>oauth_clients</i> a true, revoca il refresh token e crea un nuovo token con le stesse modalità della login.
La response di questa api, sarà uguale alla response dell'api precedente.

#### Logout
Questa sezione viene gestita nel controller <a href="app/Http/Api/User/UserController.php@logout">UserController.php@logout</a>. <br/>
In questa funzione viene revocato il token.
Se non si sono verificate eccezioni, il sistema ritorna lo stato 200.

#### Lettura dei dati

Questa sezione viene gestita nel controller <a href="app/Http/Api/ApiController/ApiController.php@get_list">ApiController.php@get_list</a>. <br/>
La funzione prevede istanzia un nuovo <i>GuzzleHttp\Client</i>  e chiama l'api https://api.openbrewerydb.org/v1/breweries in get.

Se questa risponde positivamente, viene restitua la risposta all'interno di un JsonResponse, altrtimenti viene restituita l'eccezione.

#### Note

Il token generato ha validità un'ora; esso è legato allas chiave secreta presente sulla tabella <i>oauth_clients</i>, per quel utente.

Il sistema ogni mezz'ora schedulerà un commando, che andrà a cambiare questa chiave.

Così facendo, il token che si sta utilizzando non sarà più buono.

---


### Front-end

La parte fe è stata gestitra attraverso il framework vue, vuetify per i componenti, vuerouter per la gestione delle rotte e vuex per la gestione dei dati.
Questa è stata inizializzata nella directory <i>resources</i>.<br />

Si divide:

1. Pagina Login
2. Pagina Table
3. Pagina gestione utenza:
   - refresh token
   - logout

Le chiamate api sono state realizate attraverso <i>axios</i>, creando una funzione generica che gestisce direttamente la chiamata ritornando una Promise e, sempre in questa funzione viene gestito in automatico(in base all'url dell'api che viene chiamato), se passare nella request header il bearer token.

In fase di login, se l'api risponde positivamente, il sistema inserisce nel vuex sia il token, sia il refresh token; nonchè, prima di procedere, il sistema verifica la presenza o meno del token e se questo non è scaduto. Se non fosse presente o scaduto, il sistema rimanderà l'utente alla pagina di login.

La possibilità di refresh token, all'interno della pagina di gestione utenza, è puramente a scopo di test e in un progetto reale, gestirei la chiamata o al cambiamento di pagina, oppure ad intervalli regolari, se il token ha una validità inferiore ad un'ora. 



