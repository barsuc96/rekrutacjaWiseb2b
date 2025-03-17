import * as Yup from 'yup';

export const LoginFormSchema = Yup.object().shape({
  username: Yup.string().required('Pole jest wymagane.'),
  password: Yup.string().required('Pole jest wymagane.')
});
