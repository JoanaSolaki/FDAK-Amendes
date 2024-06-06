"use client";

import { createContext, useState } from "react";

export const AppContext = createContext();

export default function AppContextProvider({ children }) {
  const [errorMessage, setErrorMessage] = useState(null);
  const [sucessMessage, setSucessMessage] = useState(null);
  const [token, setToken] = useState(null);
  const [userData, setUserData] = useState(null);

  const contextValues = {
    errorMessage,
    setErrorMessage,
    sucessMessage,
    setSucessMessage,
    token,
    setToken,
    userData,
    setUserData
  };

  return (
    <AppContext.Provider value={contextValues}>{children}</AppContext.Provider>
  );
}