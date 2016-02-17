# Finix Processing HTTP Client

[![Circle CI](https://circleci.com/gh/finix-payments/processing-php-client.svg?style=svg&circle-token=e14235e0e783121b16391bca9cca82898e3ba34e)](https://circleci.com/gh/finix-payments/processing-php-client)

### Debugging

- Install [MITM Proxy](https://mitmproxy.org/)
- `sudo mitmdump  -P http://b.papi.staging.finix.io -a -vv -p 80`
- Run the tests, see the request / response
