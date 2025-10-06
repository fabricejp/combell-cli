🧰 Combell CLI





```
A lightweight Symfony Console-based CLI to interact with the Combell API,
built on top of the TomCan/combell-api PHP SDK.

This CLI lets you query, create, and manage your Combell services such as
(accounts, domains, databases, etc.) securely — right from your terminal.
```

🚀 Features

🔧 Generic command runner: combell:run

📜 Automatic command discovery: combell:list

🔐 HMAC-based authentication via .env

🧩 JSON-formatted output for automation

🪶 Lightweight (only PHP + Composer required)

🧱 Installation

1️⃣ Clone and install dependencies

```
git clone https://github.com/fabricejp/combell-cli.git

cd combell-cli
composer install
```

2️⃣ Copy and configure environment variables

```
cp .env.example .env
```

Then edit .env and fill in your Combell API credentials:

```
COMBELL_API_KEY=your_api_key_here
COMBELL_API_SECRET=your_api_secret_here
```

⚠️ Never commit your .env file — it’s ignored by .gitignore.

🧰 Usage

All commands are executed through the Symfony runner in bin/console.

🔹 List all available Combell API commands

```
./bin/console combell:list
```

Example output:

```
Available Combell API commands:

 - AbstractCommand (params: string $method, string $endPoint)
 - Accounts\CreateAccount (params: string $identifier, int $servicePack, ?string $password = null)
 - Accounts\GetAccount (params: int $id)
 - Accounts\ListAccounts (params: string $assetType = '', string $identifier = '')
 - Dns\CreateRecord (params: string $domainName, AbstractDnsRecord $record)
 - Dns\DeleteRecord (params: string $domainName, AbstractDnsRecord $record)
 - Dns\GetRecord (params: string $domainName, string $id)
 - Dns\ListRecords (params: string $domainName)
 - Dns\UpdateRecord (params: string $domainName, AbstractDnsRecord $record)
 - Domains\GetDomain (params: string $domain)
 - Domains\ListDomains
 - Domains\RegisterDomain (params: string $domainName, array $nameServers)
 - Domains\SetNameServers (params: string $domainName, array $nameServers)
 - Domains\TransferDomain (params: string $domainName, string $authCode)
 - LinuxHostings\AddSshKey (params: string $domainName, string $pubKey)
 - LinuxHostings\ConfigureFtp (params: string $domainName, bool $enabled)
 - LinuxHostings\ConfigureSsh (params: string $domainName, bool $enabled)
 - LinuxHostings\CreateSubSite (params: string $domainName, string $subSiteDomainName, string $path = '')
 - LinuxHostings\DeleteSshKey (params: string $domainName, string $fingerprint)
 - LinuxHostings\GetAvailablePhpVersions (params: string $domainName)
 - LinuxHostings\GetLinuxHosting (params: string $domainName)
 - LinuxHostings\ListLinuxHostings
 - LinuxHostings\ListSshKeys (params: string $domainName)
 - LinuxHostings\SetAutoRedirectSsl (params: string $domainName, string $hostname, bool $enabled)
 - LinuxHostings\SetGzipCompression (params: string $domainName, bool $enabled)
 - LinuxHostings\SetHttp2 (params: string $domainName, string $siteName, bool $enabled)
 - LinuxHostings\SetLetsEncrypt (params: string $domainName, string $hostname, bool $enabled)
 - LinuxHostings\SetPhpApcu (params: string $domainName, int $apcuSize, bool $enabled)
 - LinuxHostings\SetPhpMemoryLimit (params: string $domainName, int $memoryLimit)
 - LinuxHostings\SetPhpVersion (params: string $domainName, string $phpVersion)
 - Mailboxes\CreateMailbox (params: string $domainName, string $email, string $password, int $accountId)
 - Mailboxes\GetMailboxes (params: string $domainName)
 - Mailboxes\GetQuota (params: string $domainName)
 - MysqlDatabases\CreateMysqlDatabase (params: string $database, int $account, string $password)
 - MysqlDatabases\GetMysqlDatabase (params: string $databaseName)
 - MysqlDatabases\ListMysqlDatabases
 - PageableAbstractCommand
 - ProvisioningJobs\GetProvisioningJob (params: string $jobId)
 - Servicepacks\ListServicepacks
 - Ssh\ListSshKeys
 - WindowsHostings\GetWindowsHosting (params: string $domainName)
 - WindowsHostings\ListWindowsHostings

Tip: run ./bin/console combell:run "<Command>" --params='[...]'
```

🔹 Run a specific API command

You can execute any command from the Combell SDK dynamically.

Syntax:

```
./bin/console combell:run "<CommandNamespace>" --params='[<parameters>]'
```

Examples:

List all accounts:

```
./bin/console combell:run "Accounts\ListAccounts"
```

Get a specific account:

```
./bin/console combell:run "Accounts\GetAccount" --params='[1803311]'
```


List all domains:

```
./bin/console combell:run "Domains\ListDomains"
```

Get a domain:

```
./bin/console combell:run "Domains\GetDomain" --params='["example.com"]'
```

Create a MySQL database:

```
./bin/console combell:run "MysqlDatabases\CreateMysqlDatabase" --params='["awesomedomain",1803311,"fhtjfdfdkdl,,,"]'
```

🔹 Output

All results are JSON — perfect for scripting or using with jq:

```
./bin/console combell:run "Accounts\ListAccounts" | jq .
```

Example output:

```
[
{
"id": 1803311,
"identifier": "awesomedomain.com",
"servicepackId": 15263,
"addons": []
},
{
"id": 1323758,
"identifier": "dev.awesomedomain.com",
"servicepackId": 27362,
"addons": []
}
]
```

🧩 Directory structure

```
combell-cli/
├── bin/
│   └── console                    # Main Symfony Console entrypoint
├── src/
│   └── Command/
│       ├── CombellCommand.php     # Executes API commands dynamically
│       └── ListCombellCommands.php # Lists all available API commands
├── .env.example                   # Example environment configuration
├── composer.json
├── README.md
└── .gitignore
```

⚙️ Requirements

PHP ≥ 8.1

Composer

Combell API key + secret

(Optional) jq for JSON parsing

🧩 Contributing

Pull requests are welcome!
To contribute:

Fork the repo

Create a feature branch (git checkout -b feature/my-new-command)

Commit (git commit -m 'Add new feature')

Push (git push origin feature/my-new-command)

Open a PR 🎉

🧾 License

MIT License — see LICENSE
 for details.

👤 Author

Fabrice JP
📍 Belgium
🔗 https://github.com/fabricejp

💬 Quick start

git clone https://github.com/fabricejp/combell-cli.git

cd combell-cli
composer install
cp .env.example .env

Fill in your credentials

./bin/console combell:list
./bin/console combell:run "Domains\ListDomains"

Enjoy your new lightweight Combell CLI 🚀
