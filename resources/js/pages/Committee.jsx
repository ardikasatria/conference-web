import React from 'react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import BannerCustom from '../components/BannerCustom';

// Placeholder images - replace these with actual image imports when available
const ardika_satria = '/images/placeholder-avatar.jpg';
const Dirga = '/images/placeholder-avatar.jpg';
const Efrinita = '/images/placeholder-avatar.jpg';
const Gusrian_Putra = '/images/placeholder-avatar.jpg';
const Ikah = '/images/placeholder-avatar.jpg';
const Nadisah = '/images/placeholder-avatar.jpg';
const Nisa_Yulianti_Suprahman = '/images/placeholder-avatar.jpg';
const robiatul = '/images/placeholder-avatar.jpg';
const Sofiana_Herman = '/images/placeholder-avatar.jpg';
const rektor = '/images/placeholder-avatar.jpg';
const wakilrektor = '/images/placeholder-avatar.jpg';
const wakilrektornonakademik = '/images/placeholder-avatar.jpg';
const ketuajurusansains = '/images/placeholder-avatar.jpg';

const Committee = () => {
  let commit = [
    {
      title: 'International Advisory Board',
      member: [
        'Prof. Dr. I Nyoman Pugeg Aryantha',
        'Prof. Dr. Eng. Khairurrijal, M.Si.',
        'Dr. Rahayu Sulistyorini, S.T., M.T',
      ],
      img:[rektor,wakilrektor,wakilrektornonakademik]
    },
    {
      title: 'Steering Committee',
      member: [
        'Dr. Ikah Ning Prasetiowati Permanasari, S.Si., M.Si.',
        'Dr. Sri Efrinita Irwan, S.Si., M.Si.',
        'apt. Dirga, S.Farm., M.Sc.',
      ],
      img:[ketuajurusansains,Efrinita,Dirga]
    },
    {
      title: 'General Chair',
      member: ['Dr. Robiatul Muztaba, S.Si., M.Si.'],
      img:[robiatul]
    },
    {
      title: 'Treasurer',
      member: ['Sofiana Herman, S.Si., M.Si.'],
      img:[Sofiana_Herman]
    },
    {
      title: 'Secretary',
      member: ['Nadiisah Nurul Inayah, S.Si., M.Si.'],
      img:[Nadisah]
    },
    {
      title: 'Event',
      member: ['Gusrian Putra, S.Si., M.Si.'],
      img:[Gusrian_Putra]
    },
    {
      title: 'Public Relation',
      member: ['apt. Nisa Yulianti Suprahman, S.Farm., M.Sc.'],
      img:[Nisa_Yulianti_Suprahman]
    },
    {
      title: 'Publication, Documentation and Technology',
      member: ['Ardika Satria, S.Si., M.Si.'],
      img:[ardika_satria]
    }
  ];

  return (
    <>
      <Navbar />
      <BannerCustom name="Committee" />
      <div className="mx-10 mt-36">
        {commit.map((komite) => (
          <div className="my-5 text-center flex flex-col items-center justify-center">
          <h1 className="pb-2 text-2xl text-white font-bold font-spaceGrotesk border-b-2 border-b-colorGreen my-4">
            {komite.title}
          </h1>
          <div className="flex flex-col md:flex-row gap-6">
            {komite.member.map((dosen,pos) => (
              <div className="bg-gradient-to-b from-[#121212] to-[#39FF14] to-[900%] p-5 rounded-xl flex flex-col items-center w-72">
                <img
                  src={komite.img[pos]}
                  className="w-40 h-40 object-cover object-top rounded-lg mb-3"
                />
                <div className="flex flex-col ml-3">
                  <p className="text-xl text-colorGreen font-bold text-center font-spaceGrotesk">
                    {dosen}
                  </p>
                </div>
              </div>
            ))}
          </div>
          </div>
        ))}
      </div>
      <Footer />
    </>
  );
};

export default Committee;
