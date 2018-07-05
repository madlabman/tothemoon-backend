# ToTheMoon Fund

## About
**Project Based on Laravel and Neo4j**

## Commands

#### Calculating profit of users
`php artisan tothemoon:account`
#### Calculate fund balance
`php artisan tothemoon:balance`
#### Calculate fund daily profit
`php artisan tothemoon:profit`
#### Check provided in .env BTC address for incoming payments
`php artisan tothemoon:checkBTC`

## Schedule
Every 10 minutes:

- `tothemoon:checkBTC`
- `tothemoon:balance`

Daily:

- `tothemoon:profit`

Every month:

- `tothemoon:account`
