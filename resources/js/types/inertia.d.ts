import { PageProps as InertiaPageProps } from '@inertiajs/core';

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps {
    flash?: {
      message?: string;
      success?: string;
      error?: string;
    };
  }
}