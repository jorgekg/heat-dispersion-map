import { TestBed } from '@angular/core/testing';

import { AppErrorsService } from './app-errors.service';

describe('AppErrorsService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: AppErrorsService = TestBed.get(AppErrorsService);
    expect(service).toBeTruthy();
  });
});
