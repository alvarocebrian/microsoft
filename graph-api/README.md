# Usage

Extends MicrosoftApiService and implement the method `getAccessToken`. 

Include there the logic to retrieve the access token. You should lean on the `refreshAccessToken` method to get a new token.


## Example

```php
class MicrosoftApiService extends \Strongholdam\Microsoft\GraphApi\MicrosoftApiService
{
    private const SCOPE = ['Files.ReadWrite.All', 'offline_access'];
    
    protected function getAccessToken(): string
    {
        $refreshToken = $this->getRefreshToken(); // Retrieve refresh token from DB
    
        $credentials = new Credentials($_ENV['MS_TENANT_ID'], $_ENV['MS_CLIENT_ID'], $_ENV['MS_CLIENT_SECRET'], $refreshToken);
        $credentials = $this->refreshAccessToken($credentials, self::SCOPE);
        
        $this->storeRefreshToken($credentials['refresh_token']);//Store refresh token in DB

        return $credentials['access_token'];
    }
}
```