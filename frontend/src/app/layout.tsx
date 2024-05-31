import { Audiowide, Orbitron } from "next/font/google";
import "./globals.css";
import Navbar from "@/components/Navbar/Navbar";
import { metadata } from "./metadata";
import AppContextProvider from "@/app/AppContext";

export const orbitron = Orbitron({
  subsets: ['latin'],
  weight: ['400', '900'],
});

export const audiowide = Audiowide({
  subsets: ['latin'],
  weight: ['400', '400'],
});

const metadataTitle: any = metadata.title;
const metadataDescription: any = metadata.description;

// export const metadata: Metadata = {
//   title: "FDAK Amendes",
//   description: "Site de paiement des amendes concernant les infractions envers la FDAK ou les consignes Anti-Kaijus.",
// };

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="fr">
      <AppContextProvider>
        <head>
          <title>{metadataTitle}</title>
          <meta name="description" content={metadataDescription} />
        </head>
        <body className={orbitron.className}>
          <header>
            <Navbar></Navbar>
          </header>
          {children}
        </body>
      </AppContextProvider>
    </html>
  );
}