import React from "react";
import { Link } from "@inertiajs/react";
import { ArrowRight, FileText } from 'lucide-react';

const Banner = () => {
    return (
        <div className="bannerHome h-screen relative md:-mt-[120px]">
            <div className="overlay"></div>
            <div className="bannerContent z-10 relative font-bold mt-2 flex-row font-spaceGrotesk text-center">
                <h1 className="text-2xl sm:text-2xl md:text-4xl lg:text-5xl text-white mb-2">
                    1st International Conference On Sustainability of Sciences for the Future (ICSSF)
                </h1>
                <h1 className="text-colorGreen text-2xl sm:text-2xl md:text-3xl lg:text-3xl pt-5">
                    September 26-27th, 2024
                </h1>
                <h1 className="text-xl sm:text-xl md:text-2xl lg:text-2xl text-white italic font-plusJakarta">
                    "Building Up The Next Generation of Scientists"
                </h1>
                <div className="mt-[50px] flex items-center justify-center">
                    <Link href="/submissions" className="bg-colorGreen flex w-fit py-2 px-5 items-center rounded-full gap-2 text-black font-bold hover:bg-white transition">
                        <FileText className="w-5 h-5" /> Call for Paper
                    </Link>
                </div>
            </div>
            <ul className="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    );
};

export default Banner;
