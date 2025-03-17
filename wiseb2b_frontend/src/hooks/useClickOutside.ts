// hook obsługujący kliknięcie poza elementem

import { RefObject, useEffect } from 'react';

export const useClickOutside = (refs: RefObject<HTMLDivElement>[], handler: () => void) => {
  useEffect(() => {
    const listener = (event: TouchEvent | MouseEvent) => {
      const isClickOutside = refs.some(
        (ref) => ref.current && ref.current.contains(event.target as Node)
      );

      !isClickOutside && handler();
    };

    document.addEventListener('mousedown', listener);
    document.addEventListener('touchstart', listener);
    return () => {
      document.removeEventListener('mousedown', listener);
      document.removeEventListener('touchstart', listener);
    };
  }, [refs, handler]);
};
