"use client";

import { createContext, useState } from "react";

export const AppContext = createContext();

export default function AppContextProvider({ children }) {
  const [message, setMessage] = useState(null);
  const [token, setToken] = useState(null);

  const contextValues = {
    message,
    setMessage,
    token,
    setToken
  };

  return (
    <AppContext.Provider value={contextValues}>{children}</AppContext.Provider>
  );
}
