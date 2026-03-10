import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '../Components/Navbar';
import Banner from '../Components/Banner';
import About from '../Components/About';
import Timeline from '../Components/Timeline';
import Topics from '../Components/Topics';
import Keynote from '../Components/Keynote';
import Speaker from '../Components/Speaker';
import Pricelist from '../Components/Pricelist';
import Countdown from '../Components/Countdown';
import Venue from '../Components/Venue';
import Trip from '../Components/Trip';
import Contact from '../Components/Contact';
import Faq from '../Components/Faq';
import Footer from '../Components/Footer';

export default function Home() {
  return (
    <>
      <Head>
        <title>ICSSF 2026 - International Conference on Sustainability of Sciences for the Future</title>
        <meta name="description" content="1st International Conference on Sustainability of Sciences for the Future" />
      </Head>

      <Navbar />
      <Banner />
      <About />
      <Timeline />
      <Topics />
      <Keynote />
      <Speaker />
      <Pricelist />
      <Countdown />
      <Venue />
      <Trip />
      <Contact />
      <Faq />
      <Footer />
    </>
  );
}
