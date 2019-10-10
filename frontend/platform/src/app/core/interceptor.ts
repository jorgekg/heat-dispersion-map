import { Injectable } from '@angular/core';
import {
  HttpInterceptor,
  HttpRequest
} from '@angular/common/http';
import { AppStorageService } from './app-storage/app-storage.service';

@Injectable()
export class Interceptor implements HttpInterceptor {
  
  constructor(
    private appStorageService: AppStorageService
  ) {}

  intercept(req, next): any {
    const token = this.appStorageService.getToken();
    if (token && token.token) {
      const authReq = req.clone({
        headers: req.headers.set('Authorization', token.token)
      });
      return next.handle(authReq);
    }
    return next.handle(req);
  }
}