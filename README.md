# 🧠 FAQ Chatbot

An AI-powered Retrieval-Augmented Generation (RAG) chatbot, built using **n8n** and the **Google Gemini** model.  
It allows users to upload documents (`.pdf`, `.csv`, `.docx`, `.doc`) and query them through an embeddable chat widget with real-time, context-aware answers.

---

## 📌 Features
- 📂 **Document Upload** – Supports PDF, CSV, DOCX, and DOC formats.
- 🧠 **Vector-Based Search** – Uses in-memory vector storage for fast retrieval.
- 🤖 **Google Gemini Integration** – Leverages LLM capabilities for intelligent responses.
- 💬 **Embeddable Chat Widget** – Enables AI-powered workflows directly in a chat window.
- 🎨 **Custom UI** – CSS-based interface, implemented from scratch for styling and usability.
- 🗨 **Context-Aware Conversations** – Maintains chat history for multi-turn dialogue.
  
---

## 🚀 Tech Stack
- **n8n** – Workflow automation platform
- **Google Gemini API** – Language model for embeddings & chat generation
- **JavaScript** – Frontend interactivity
- **Tailwind CSS** – Styling and UI components
- **HTML/CSS** – Chat widget structure
- **JSON** – Workflow configuration

---

## 📂 Project Structure
│── chatbot.html # Chat widget UI

│── TRIVESTA-CHATBOT.json # n8n workflow definition

---

## ⚙️ Installation & Setup

1. **Clone the Repository**
```bash
git clone https://github.com/Sammit07/RAG-Chatbot.git
cd RAG-Chatbot
Start your n8n instance and activate the workflow.
```

---

## 🧩 WordPress Integration (Plugin)

This repo includes a minimal WordPress plugin at `wp-trivesta-chatbot/` that embeds the n8n chat widget via shortcode.

### 1) Install the plugin
- Copy the entire `wp-trivesta-chatbot` folder into your WordPress site at `wp-content/plugins/`.
- In WordPress Admin, go to Plugins and activate “Trivesta Chatbot (n8n)”.

### 2) Configure the webhook
- Go to Settings → Trivesta Chatbot.
- Set Webhook URL to your public n8n Chat Trigger webhook, for example:
  - `https://your-n8n-host/webhook/<webhook-id>/chat`
- Optionally adjust display mode, welcome screen, and initial messages.

Notes:
- Ensure your n8n Chat Trigger is set to public and reachable from your WordPress domain.
- If using a Content Security Policy (CSP), allow `https://cdn.jsdelivr.net` for `script-src` and `style-src`.

### 3) Use the shortcode
Add the shortcode to any post or page:

```
[trivesta_chatbot]
```

Override options per page if needed:

```
[trivesta_chatbot 
  webhook_url="https://your-n8n-host/webhook/<webhook-id>/chat" 
  mode="window" 
  show_welcome="1" 
  initial_messages="Hi there!|How can I help you today?" 
  load_previous="1" 
  enable_streaming="0"
]
```

### 4) Local HTML demo (optional)
You can also open `chatbot.html` for a standalone demo. Update its `webhookUrl` to your public n8n URL (not `localhost`) when testing outside your machine.
