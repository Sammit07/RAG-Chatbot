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
