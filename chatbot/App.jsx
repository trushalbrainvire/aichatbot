import { useEffect, useRef, useState } from "react";
import ChatbotIcon from "./components/ChatbotIcon";
import ChatForm from "./components/ChatForm";
import ChatMessage from "./components/ChatMessage";

const App = () => {
  const chatBodyRef = useRef();
  const [chatLoading,setChatLoading] = useState(true);
  const [showChatbot, setShowChatbot] = useState(false);
  const [chatHistory, setChatHistory] = useState([
    {
      hideInChat: true,
      role: "model",
      text: "",
    },
  ]);

  const generateBotResponse = async (history) => {
    // Helper function to update chat history
    const updateHistory = (text, isError = false) => {
      setChatHistory((prev) => [...prev.filter((msg) => msg.text != "Thinking..."), { role: "model", text, isError }]);
    };

    // Format chat history for API request
    history = history.map(({ role, text }) => ({ role, parts: [{ text }] }));

    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json", "accept": "application/json" },
      body: JSON.stringify({ message: { contents: history } }),
    };

    try {
      // Make the API call to get the bot's response
      const response = await fetch(`/apps/proxy/message`, requestOptions);
      const data = await response.json();
      if (!response.ok) throw new Error(data?.error.message || "Something went wrong!");

      // Clean and update chat history with bot's response

      const apiResponseText = data.data.replace(/\*\*(.*?)\*\*/g, "$1").trim();
      updateHistory(apiResponseText);
    } catch (error) {
      // Update chat history with the error message
      updateHistory(error.message, true);
    }
  };

  useEffect(()=>{
    const updateHistory = (text, isError = false) => {
      setChatHistory((prev) => [...prev.filter((msg) => msg.text != "Thinking..."), { role: "model", text, isError }]);
    };

    async function chatInitiation(){
      const response = await fetch(`/apps/proxy/initiation`);
      const data = await response.json();
      if (!response.ok) throw new Error(data?.error.message || "Something went wrong!");
      if (data.status != "success") {
        updateHistory(data.errors, true);
      }
      updateHistory(data.data);
      setChatLoading(false)
    }
    chatInitiation()
  },[])

  useEffect(() => {
    // Auto-scroll whenever chat history updates
    chatBodyRef.current.scrollTo({ top: chatBodyRef.current.scrollHeight, behavior: "smooth" });
  }, [chatHistory]);

  return (
    <div className={`container ${showChatbot ? "show-chatbot" : ""}`}>
      <button onClick={() => setShowChatbot((prev) => !prev)} id="chatbot-toggler">
        <span className="material-symbols-rounded">mode_comment</span>
        <span className="material-symbols-rounded">close</span>
      </button>

      <div className="chatbot-popup">
        {/* Chatbot Header */}
        <div className="chat-header">
          <div className="header-info">
            <ChatbotIcon />
            <h2 className="logo-text">Chatbot</h2>
          </div>
          <button onClick={() => setShowChatbot((prev) => !prev)} className="material-symbols-rounded">
            keyboard_arrow_down
          </button>
        </div>

        {/* Chatbot Body */}
        <div ref={chatBodyRef} className="chat-body">
          {
            chatLoading ?
              <div className="message bot-message">
                <ChatbotIcon />
                <p className="message-text">
                  <span className="skeleton-placeholder"></span>
                  <span className="skeleton-placeholder"></span>
                </p>
              </div>
            :
              chatHistory.map((chat, index) => (
                <ChatMessage key={index} chat={chat} />
              ))
          }
        </div>

        {/* Chatbot Footer */}
        <div className="chat-footer">
          <ChatForm chatHistory={chatHistory} setChatHistory={setChatHistory} generateBotResponse={generateBotResponse} chatLoading={chatLoading} />
        </div>
      </div>
    </div>
  );
};

export default App;
