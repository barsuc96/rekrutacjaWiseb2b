/* eslint-disable @typescript-eslint/no-explicit-any */
export type IComponentParams = {
  isEditorInitialVisible?: boolean;
  isModal?: boolean;
  fnCallback?: (e?: any) => void;
  onErrorCallback?: (e?: any) => void;
  refetchFn?: () => void;
};
