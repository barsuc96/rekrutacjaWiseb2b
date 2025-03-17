import React, { FC, useEffect, useRef } from 'react';

import { DomElement } from 'html-react-parser';
import domToReact from 'html-react-parser/lib/dom-to-react';

interface IProps {
  domAttrs: Record<string, unknown>;
  children: DomElement;
}

export const Video: FC<IProps> = ({ domAttrs, children }) => {
  const videoRef = useRef<HTMLVideoElement>(null);

  const handleScroll = () => {
    const element = videoRef.current;
    const y = element?.getBoundingClientRect().y;

    if (y && y < window.innerHeight) {
      element.play();
    }
  };

  useEffect(() => {
    window.addEventListener('scroll', handleScroll);
  }, []);

  const { controls, ...restAttrs } = domAttrs;

  return (
    <video preload="auto" autoPlay playsInline ref={videoRef} {...restAttrs} loop muted>
      {domToReact(children)}
    </video>
  );
};

export default Video;
