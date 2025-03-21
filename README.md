# YOURLS IP Information Plugin

A comprehensive plugin for YOURLS (Your Own URL Shortener) that provides detailed IP address information for link clicks, including geolocation, ISP details, VPN detection, and bot identification.

## Features

- **Detailed IP Analytics**: View comprehensive information about each visitor
  - Geolocation (city, region, country, continent)
  - ISP and network details
  - Device and browser identification
  - VPN/Proxy detection
  - Bot detection

- **User-Friendly Interface**:
  - Clean, modern design with Material Design elements
  - Expandable details for each click
  - Statistical overview (total clicks, unique IPs, countries, etc.)
  - One-click filtering to show/hide bot traffic

- **Smart Device Detection**:
  - Identifies browsers, operating systems, and device types
  - Special detection for mobile apps (Facebook, Instagram, Twitter, etc.)
  - Recognizes popular browsers and applications

- **Referrer Tracking**:
  - See where your traffic is coming from
  - Identifies direct links vs referrals

## Installation

1. Download the plugin files
2. Copy the `yourl_ip_info` folder to your YOURLS `user/plugins/` directory
3. Activate the plugin in the YOURLS admin interface

## Requirements

- YOURLS 1.7 or higher
- PHP 5.6 or higher
- Access to external API (ip-api.com)

## Usage

After installation and activation, visit any stats page for your shortened URLs. The plugin automatically adds detailed IP information to the stats display.

- Click "Show Details" on any entry to view comprehensive IP information
- Use the "Hide Bots" button to filter out bot traffic
- Your own IP address will be highlighted for easy identification

## Credits

- Created by InfoSecREDD
- Uses [ip-api.com](https://ip-api.com) for IP information
