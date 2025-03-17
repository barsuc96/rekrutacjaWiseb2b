// hook zwracający informacje czy skrolujemy w góre, czy w dół

import { useEffect, useState } from 'react';

export const useScrollDown = () => {
  const [isScrollDown, setIsScrollDown] = useState(false);

  let scrollY = document.getElementsByClassName('scrollableContent')[0]?.scrollTop || 0;
  function logit() {
    const nextScroll = document.getElementsByClassName('scrollableContent')[0]?.scrollTop;
    if (scrollY < nextScroll) {
      setIsScrollDown(true);
    } else {
      setIsScrollDown(false);
    }
    scrollY = nextScroll;
  }

  useEffect(() => {
    function watchScroll() {
      document.getElementsByClassName('scrollableContent')[0]?.addEventListener('scroll', logit);
    }
    watchScroll();
    return () => {
      document.getElementsByClassName('scrollableContent')[0]?.removeEventListener('scroll', logit);
    };
  }, []);
  return {
    isScrollDown
  };
};
