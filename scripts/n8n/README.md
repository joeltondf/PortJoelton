# n8n Workflow Configuration

To automate your leads, follow these steps in your n8n instance:

1. **Webhook Node**:
    - Method: POST
    - Path: `/contact-form`
    - Response Mode: `onReceive`
    - Response Code: `200`
    
2. **Discord / Telegram Node** (Optional):
    - Connect to your preferred messaging service.
    - Set the message content to: 
      `🚀 Novo Lead do Portfólio! \n👤 Nome: {{$json["name"]}} \n📧 E-mail: {{$json["email"]}} \n📝 Mensagem: {{$json["message"]}}`

3. **Email (SMTP) Node**:
    - Subject: "Novo Lead no Portfólio"
    - Recipient: your-email@example.com
    - Body (HTML): Create a beautiful notification template using the incoming JSON data.

## Environment Variable
Make sure to set the `N8N_WEBHOOK_URL` in your `docker-compose.yml` or `.env` file to point to your n8n Production Webhook URL.
