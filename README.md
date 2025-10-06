🧰 Combell CLI






A lightweight Symfony Console-based CLI to interact with the Combell API
, built on top of the TomCan/combell-api
 PHP SDK.

This CLI lets you query, create, and manage your Combell services (accounts, domains, databases, etc.) securely — right from your terminal.

🚀 Features

🔧 Generic command runner: combell:run

📜 Automatic command discovery: combell:list

🔐 HMAC-based authentication via .env

🧩 JSON-formatted output for automation

🪶 Lightweight (only PHP + Composer required)

🧱 Installation
1️⃣ Clone and install dependencies

git clone https://github.com/fabricejp/combell-cli.git

cd combell-cli
composer install

2️⃣ Copy and configure environment variables

cp .env.example .env

Then edit .env and fill in your Combell API credentials:

COMBELL_API_KEY=your_api_key_here
COMBELL_API_SECRET=your_api_secret_here

⚠️ Never commit your .env file — it’s ignored by .gitignore.

🧰 Usage

All commands are executed through the Symfony runner in bin/console.

🔹 List all available Combell API commands

./bin/console combell:list

Example output:
Available Combell API commands:

Accounts\ListAccounts ()

Accounts\GetAccount (params: int $id)

Domains\ListDomains ()

Domains\GetDomain (params: string $domainName)

MysqlDatabases\ListMysqlDatabases (params: int $account)

MysqlDatabases\CreateMysqlDatabase (params: string $database, int $account, string $password)

Tip: run ./bin/console combell:run "<Command>" --params='[...]'

🔹 Run a specific API command

You can execute any command from the Combell SDK dynamically.

Syntax:
./bin/console combell:run "<CommandNamespace>" --params='[<parameters>]'

Examples:

List all accounts:
./bin/console combell:run "Accounts\ListAccounts"

Get a specific account:
./bin/console combell:run "Accounts\GetAccount" --params='[1803311]'

List all domains:
./bin/console combell:run "Domains\ListDomains"

Get a domain:
./bin/console combell:run "Domains\GetDomain" --params='["example.com"]'

Create a MySQL database:
./bin/console combell:run "MysqlDatabases\CreateMysqlDatabase" --params='["awesomedomain",1803311,"fhtjfdfdkdl,,,"]'

🔹 Output

All results are JSON — perfect for scripting or using with jq:

./bin/console combell:run "Accounts\ListAccounts" | jq .

Example output:
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

🧩 Directory structure

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


⚙️ Requirements

PHP ≥ 8.1

Composer

Combell API key + secret

(Optional) jq for JSON parsing

🧪 Troubleshooting
Problem	Cause	Fix
Missing COMBELL_API_KEY or COMBELL_API_SECRET	.env not set	Copy .env.example → .env and fill in credentials
Combell API library not found	Wrong vendor path	Ensure vendor/tomcan/combell-api/src/Command exists
Repository not found	Wrong GitHub username	Update with git remote set-url origin git@github.com:fabricejp/combell-cli.git
🐳 Optional: Run with Docker

docker run --rm -it
-v $(pwd):/app
-w /app
php:8.3-cli
bash -c "apt-get update && apt-get install -y git unzip && php -r "copy('https://getcomposer.org/installer
', 'composer-setup.php');" && php composer-setup.php && ./composer.phar install && ./bin/console combell:list"

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
