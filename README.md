# Combell CLI

A lightweight Symfony Console-based CLI to interact with the [Combell API](https://api.combell.com/v2/), built on top of the [TomCan/combell-api](https://github.com/TomCan/combell-api) PHP SDK.

## 🚀 Features
- Generic command runner: `combell:run`
- Dynamic API command discovery: `combell:list`
- Secure `.env`-based HMAC authentication
- JSON-formatted output for automation

## 🧱 Installation

```bash
git clone https://github.com/<your-username>/combell-cli.git
cd combell-cli
composer install
cp .env.example .env

