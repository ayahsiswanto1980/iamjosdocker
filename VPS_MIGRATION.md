# IAMJOS: VPS Migration Guide

This guide explains how to migrate your application from Railway to a Self-Managed VPS (DigitalOcean, Vultr, etc.) using the Docker configuration we've prepared.

## Prerequisites
1. A VPS with **Ubuntu 22.04/24.04**.
2. **Docker** and **Docker Compose** installed.
3. A Domain name pointing to your VPS IP.

## Step 1: Prepare the VPS
Connect to your VPS and install Docker:
```bash
# Install Docker (Quick Script)
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
```

## Step 2: Clone & Configure
Clone your repository and setup the environment:
```bash
git clone https://github.com/yourusername/iamjosphp.git
cd iamjosphp
cp .env.example .env
```
Edit the `.env` file with your production secrets (APP_KEY, DB_PASSWORD, etc.).

## Step 3: Launch with Docker Compose
Run the following command to start the application and database:
```bash
docker compose up -d
```
The application will be available at `http://your-vps-ip:8080`.

## Step 4: Setup SSL & Reverse Proxy (Recommended)
We recommend using **Coolify** or **Nginx Proxy Manager** for easy SSL (HTTPS) management. 

If using **Coolify**:
1. Install Coolify: `curl -fsSL https://get.coollabs.io/install.sh | bash`
2. Connect your GitHub Repo.
3. Select "Docker Compose" deployment.
4. Coolify will handle the rest, including automated HTTPS.

## Step 5: Data Migration (from Railway)
Exporters and importers:
1. **Database**: Use `pg_dump` on Railway and `psql` on the VPS to migrate data.
2. **Files**: If using `local` disk, copy the `storage/app` directory to the VPS.

---
**Note:** The current Docker setup uses `ports: 8080:8080`. You can change this in `docker-compose.yml` to suit your VPS needs.
